<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Company;
use App\Models\Vehicle;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\InsuranceCompany;
use App\Models\Liquidator;
use App\Models\UnType;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // ─── UnTypes are seeded by the migration itself ───────────────────────
        $rep  = UnType::where('code', 'REP')->first();
        $pint = UnType::where('code', 'PINT')->first();
        $dm   = UnType::where('code', 'D/M')->first();
        $cam  = UnType::where('code', 'C')->first();
        $mat  = UnType::where('code', 'MAT')->first();

        // 1. Insurance Companies
        $cardif = InsuranceCompany::create(['name' => 'Cardif']);
        $bci    = InsuranceCompany::create(['name' => 'BCI Seguros']);

        // 2. Liquidators
        $liquidator1 = Liquidator::create([
            'name'                 => 'Juan Perez',
            'insurance_company_id' => $cardif->id,
            'phone'                => '+56912345678',
            'email'                => 'juan.perez@cardif.cl',
        ]);

        // 3. Clients
        $client1 = Client::create([
            'rut_dni' => '12.345.678-9',
            'name'    => 'Nelson Edgardo Locer',
            'phone'   => '+56987654321',
            'email'   => 'nelson@example.com',
            'address' => 'Juan Enrique Lira 3580, Viña del Mar',
        ]);
        $client2 = Client::create([
            'rut_dni' => '15.678.901-2',
            'name'    => 'María José García',
            'phone'   => '+56911223344',
            'email'   => 'mariajose@example.com',
            'address' => 'Av. Libertad 1020, Viña del Mar',
        ]);

        // 4. Vehicles
        $vehicle1 = Vehicle::create([
            'license_plate' => 'GFGR-60',
            'brand'         => 'Kia',
            'model'         => 'Carens',
            'year'          => 2018,
            'color'         => 'Plateado',
            'vin_chassis'   => 'KNAFX412BCDS123456',
            'odometer'      => 85593,
            'client_id'     => $client1->id,
        ]);
        $vehicle2 = Vehicle::create([
            'license_plate' => 'ABCD-12',
            'brand'         => 'Hyundai',
            'model'         => 'Tucson',
            'year'          => 2022,
            'color'         => 'Blanco',
            'vin_chassis'   => 'HNDYX9988AA112233',
            'odometer'      => 15200,
            'client_id'     => $client2->id,
        ]);

        // 5. Quotations ────────────────────────────────────────────────────────
        // quote1: aprobada, con folio asignado
        $items1 = [
            ['un_type_id' => $rep->id,  'description' => 'Parachoques trasero — Reparación',             'price' => 100000, 'is_salvage' => false],
            ['un_type_id' => $pint->id, 'description' => 'Parachoques trasero — Pintura',                'price' =>  67800, 'is_salvage' => false],
            ['un_type_id' => $dm->id,   'description' => 'Parachoques trasero — Desmontar/Montar',       'price' =>  16000, 'is_salvage' => false],
            ['un_type_id' => $rep->id,  'description' => 'Guardafango plástico trasero der. — Reparar',  'price' =>  55000, 'is_salvage' => false],
            ['un_type_id' => $pint->id, 'description' => 'Guardafango plástico trasero der. — Pintura',  'price' =>  12000, 'is_salvage' => false],
            ['un_type_id' => $dm->id,   'description' => 'Guardafango plástico trasero der. — D/M',      'price' =>   3500, 'is_salvage' => false],
        ];
        $neto1 = collect($items1)->sum('price');
        $tax1  = round($neto1 * 0.19);

        $quote1 = Quotation::create([
            'folio'                => '1423',
            'date'                 => Carbon::parse('2026-01-05'),
            'status'               => 'approved',
            'vehicle_id'           => $vehicle1->id,
            'client_id'            => $client1->id,
            'insurance_company_id' => $cardif->id,
            'liquidator_id'        => $liquidator1->id,
            'tax_amount'           => $tax1,
            'total_amount'         => $neto1 + $tax1,
            'notes'                => 'Reparación de parachoques trasero según presupuesto original.',
        ]);
        $this->insertItems($quote1->id, $items1);

        // quote2: borrador sin folio (muestra el flujo nuevo)
        $items2 = [
            ['un_type_id' => $pint->id, 'description' => 'Revisión general de pintura', 'price' => 150000, 'is_salvage' => false],
            ['un_type_id' => $mat->id,  'description' => 'Material de preparación',     'price' =>  25000, 'is_salvage' => false],
        ];
        $neto2 = collect($items2)->sum('price');
        $tax2  = round($neto2 * 0.19);

        $quote2 = Quotation::create([
            'folio'        => null,
            'date'         => Carbon::now(),
            'status'       => 'draft',
            'vehicle_id'   => $vehicle2->id,
            'client_id'    => $client2->id,
            'tax_amount'   => $tax2,
            'total_amount' => $neto2 + $tax2,
            'notes'        => 'Revisión inicial por falla de pintura.',
        ]);
        $this->insertItems($quote2->id, $items2);

        // 6. Set folio counter so next sent quotation starts at 1424
        Company::current()->update(['folio_counter' => 1424]);
    }

    private function insertItems(int $quotationId, array $items): void
    {
        $now  = now();
        $rows = array_map(fn($i) => array_merge($i, [
            'quotation_id' => $quotationId,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]), $items);

        QuotationItem::insert($rows);
    }
}
