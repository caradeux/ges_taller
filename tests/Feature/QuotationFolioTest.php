<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Client;
use App\Models\Company;
use App\Models\Quotation;
use App\Models\UnType;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class QuotationFolioTest extends TestCase
{
    use RefreshDatabase;

    // ─── Helpers ────────────────────────────────────────────────────────────

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin', 'active' => true]);
    }

    private function makeRecepcion(): User
    {
        return User::factory()->create(['role' => 'recepcion', 'active' => true]);
    }

    /** Creates the minimum required records and returns a draft quotation */
    private function makeDraftQuotation(User $user): Quotation
    {
        $client  = Client::create(['name' => 'Test Cliente', 'rut_dni' => fake()->unique()->numerify('##.###.###-#')]);
        $vehicle = Vehicle::create([
            'license_plate' => 'TEST-' . rand(10, 99),
            'brand'         => 'Toyota',
            'model'         => 'Corolla',
            'client_id'     => $client->id,
        ]);
        $unType = UnType::first(); // seeded by migration

        $response = $this->actingAs($user)->post(route('quotations.store'), [
            'client_id'  => $client->id,
            'vehicle_id' => $vehicle->id,
            'date'       => now()->format('Y-m-d'),
            'items'      => [
                ['un_type_id' => $unType->id, 'description' => 'Trabajo de prueba', 'price' => '100000'],
            ],
        ]);

        $response->assertRedirect();

        return Quotation::latest('id')->first();
    }

    // ─── Draft creation ──────────────────────────────────────────────────────

    #[Test]
    public function draft_quotation_is_created_without_folio(): void
    {
        $admin     = $this->makeAdmin();
        $quotation = $this->makeDraftQuotation($admin);

        $this->assertEquals('draft', $quotation->status);
        $this->assertNull($quotation->folio);
    }

    #[Test]
    public function folio_display_returns_borrador_when_folio_is_null(): void
    {
        $admin     = $this->makeAdmin();
        $quotation = $this->makeDraftQuotation($admin);

        $this->assertEquals('Borrador', $quotation->folio_display);
    }

    #[Test]
    public function folio_counter_is_not_advanced_on_draft_creation(): void
    {
        $admin          = $this->makeAdmin();
        $counterBefore  = Company::current()->folio_counter;

        $this->makeDraftQuotation($admin);

        $this->assertEquals($counterBefore, Company::current()->fresh()->folio_counter);
    }

    // ─── Folio assignment on status → sent ──────────────────────────────────

    #[Test]
    public function folio_is_assigned_when_status_changes_to_sent(): void
    {
        $admin     = $this->makeAdmin();
        $quotation = $this->makeDraftQuotation($admin);

        $this->actingAs($admin)
            ->post(route('quotations.status', $quotation), ['status' => 'sent'])
            ->assertRedirect();

        $quotation->refresh();

        $this->assertEquals('sent', $quotation->status);
        $this->assertNotNull($quotation->folio);
    }

    #[Test]
    public function folio_is_padded_to_four_digits(): void
    {
        Company::current()->update(['folio_counter' => 5]);

        $admin     = $this->makeAdmin();
        $quotation = $this->makeDraftQuotation($admin);

        $this->actingAs($admin)
            ->post(route('quotations.status', $quotation), ['status' => 'sent']);

        $this->assertEquals('0005', $quotation->fresh()->folio);
    }

    #[Test]
    public function folio_counter_is_incremented_after_assignment(): void
    {
        $company = Company::current();
        $company->update(['folio_counter' => 10]);

        $admin     = $this->makeAdmin();
        $quotation = $this->makeDraftQuotation($admin);

        $this->actingAs($admin)
            ->post(route('quotations.status', $quotation), ['status' => 'sent']);

        $this->assertEquals(11, $company->fresh()->folio_counter);
    }

    #[Test]
    public function folio_is_not_reassigned_if_already_has_one(): void
    {
        $admin     = $this->makeAdmin();
        $quotation = $this->makeDraftQuotation($admin);

        // First transition: draft → sent (assigns folio)
        $this->actingAs($admin)
            ->post(route('quotations.status', $quotation), ['status' => 'sent']);

        $folio = $quotation->fresh()->folio;
        $this->assertNotNull($folio);

        // Simulate going back to draft then to sent again — folio must not change
        $quotation->update(['status' => 'draft']);

        $this->actingAs($admin)
            ->post(route('quotations.status', $quotation), ['status' => 'sent']);

        $this->assertEquals($folio, $quotation->fresh()->folio);
    }

    #[Test]
    public function folio_is_not_assigned_on_other_status_transitions(): void
    {
        $admin     = $this->makeAdmin();
        $quotation = $this->makeDraftQuotation($admin);

        // Transition to approved (skipping sent) should not assign folio
        $this->actingAs($admin)
            ->post(route('quotations.status', $quotation), ['status' => 'approved']);

        $this->assertNull($quotation->fresh()->folio);
    }

    // ─── Sequential uniqueness (simulated) ──────────────────────────────────

    #[Test]
    public function sequential_folios_are_unique_across_multiple_quotations(): void
    {
        Company::current()->update(['folio_counter' => 1]);

        $admin = $this->makeAdmin();

        $q1 = $this->makeDraftQuotation($admin);
        $q2 = $this->makeDraftQuotation($admin);
        $q3 = $this->makeDraftQuotation($admin);

        $this->actingAs($admin)->post(route('quotations.status', $q1), ['status' => 'sent']);
        $this->actingAs($admin)->post(route('quotations.status', $q2), ['status' => 'sent']);
        $this->actingAs($admin)->post(route('quotations.status', $q3), ['status' => 'sent']);

        $folios = [$q1->fresh()->folio, $q2->fresh()->folio, $q3->fresh()->folio];

        // All three folios are distinct
        $this->assertCount(3, array_unique($folios));

        // And they are sequential
        $this->assertEquals(['0001', '0002', '0003'], $folios);
    }

    // ─── PDF protection ─────────────────────────────────────────────────────

    #[Test]
    public function pdf_download_is_blocked_for_draft_without_folio(): void
    {
        $admin     = $this->makeAdmin();
        $quotation = $this->makeDraftQuotation($admin);

        $response = $this->actingAs($admin)
            ->get(route('quotations.pdf', $quotation));

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    #[Test]
    public function pdf_download_is_allowed_after_folio_is_assigned(): void
    {
        $admin     = $this->makeAdmin();
        $quotation = $this->makeDraftQuotation($admin);

        $this->actingAs($admin)
            ->post(route('quotations.status', $quotation), ['status' => 'sent']);

        $response = $this->actingAs($admin)
            ->get(route('quotations.pdf', $quotation->fresh()));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
