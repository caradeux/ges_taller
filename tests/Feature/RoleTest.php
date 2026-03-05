<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    // ─── Helpers ────────────────────────────────────────────────────────────

    private function makeAdmin(): User
    {
        return User::factory()->create([
            'role'   => 'admin',
            'active' => true,
        ]);
    }

    private function makeUser(string $role): User
    {
        return User::factory()->create([
            'role'   => $role,
            'active' => true,
        ]);
    }

    /**
     * The create_roles_table migration seeds the three system roles.
     * RefreshDatabase runs migrations fresh each time, so those rows
     * are always present. We simply fetch one to keep tests concise.
     */
    private function adminRole(): Role
    {
        return Role::where('name', 'admin')->firstOrFail();
    }

    // ─── GET /roles ──────────────────────────────────────────────────────────

    #[Test]
    public function admin_can_view_roles_index(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->get(route('roles.index'));

        $response->assertStatus(200);
        $response->assertViewIs('roles.index');
        $response->assertViewHas('roles');
    }

    #[Test]
    public function recepcion_cannot_access_roles_index(): void
    {
        $user = $this->makeUser('recepcion');

        $response = $this->actingAs($user)->get(route('roles.index'));

        // CheckRolePermission redirects non-admin to dashboard with an error message
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    #[Test]
    public function taller_cannot_access_roles_index(): void
    {
        $user = $this->makeUser('taller');

        $response = $this->actingAs($user)->get(route('roles.index'));

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    // ─── PUT /roles/{role} ───────────────────────────────────────────────────

    #[Test]
    public function admin_can_update_role_label_description_and_badge_color(): void
    {
        $admin = $this->makeAdmin();
        $role  = $this->adminRole();

        $response = $this->actingAs($admin)->put(route('roles.update', $role), [
            'label'       => 'Super Administrador',
            'description' => 'Descripción actualizada.',
            'badge_color' => '#ff0000',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $role->refresh();
        $this->assertSame('Super Administrador', $role->label);
        $this->assertSame('Descripción actualizada.', $role->description);
        $this->assertSame('#ff0000', $role->badge_color);
    }

    #[Test]
    public function update_requires_label(): void
    {
        $admin = $this->makeAdmin();
        $role  = $this->adminRole();

        $response = $this->actingAs($admin)->put(route('roles.update', $role), [
            'label'       => '',           // intentionally blank
            'description' => 'Sin label.',
            'badge_color' => '#123456',
        ]);

        $response->assertSessionHasErrors(['label']);
    }

    #[Test]
    public function update_requires_badge_color(): void
    {
        $admin = $this->makeAdmin();
        $role  = $this->adminRole();

        $response = $this->actingAs($admin)->put(route('roles.update', $role), [
            'label'       => 'Válido',
            'description' => null,
            'badge_color' => '',           // intentionally blank
        ]);

        $response->assertSessionHasErrors(['badge_color']);
    }

    #[Test]
    public function recepcion_cannot_update_a_role(): void
    {
        $user = $this->makeUser('recepcion');
        $role = $this->adminRole();

        $response = $this->actingAs($user)->put(route('roles.update', $role), [
            'label'       => 'Intento no autorizado',
            'description' => null,
            'badge_color' => '#000000',
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    #[Test]
    public function taller_cannot_update_a_role(): void
    {
        $user = $this->makeUser('taller');
        $role = $this->adminRole();

        $response = $this->actingAs($user)->put(route('roles.update', $role), [
            'label'       => 'Intento no autorizado',
            'description' => null,
            'badge_color' => '#000000',
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }
}
