<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Company;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use App\Models\WorkOrderEvent;
use App\Models\InsuranceCompany;
use App\Models\Liquidator;
use App\Models\Tag;
use App\Models\UnType;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Skip if sample data already exists
        if (InsuranceCompany::exists()) {
            return;
        }

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

        // 3. Tags
        Tag::create(['name' => 'Urgente', 'slug' => 'urgente', 'color' => '#dc2626']);
        Tag::create(['name' => 'Pendiente de Repuesto', 'slug' => 'pendiente-de-repuesto', 'color' => '#d97706']);
        Tag::create(['name' => 'Re-inspección', 'slug' => 're-inspeccion', 'color' => '#7c3aed']);

        // 4. Clients
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

        // 5. Vehicles
        $vehicle1 = Vehicle::create([
            'license_plate' => 'GFGR60',
            'brand'         => 'Kia',
            'model'         => 'Carens',
            'year'          => 2018,
            'color'         => 'Plateado',
            'vin_chassis'   => 'KNAFX412BCDS123456',
            'odometer'      => 85593,
            'client_id'     => $client1->id,
        ]);
        $vehicle2 = Vehicle::create([
            'license_plate' => 'ABCD12',
            'brand'         => 'Hyundai',
            'model'         => 'Tucson',
            'year'          => 2022,
            'color'         => 'Blanco',
            'vin_chassis'   => 'HNDYX9988AA112233',
            'odometer'      => 15200,
            'client_id'     => $client2->id,
        ]);

        // 6. Work Orders ────────────────────────────────────────────────────────
        // OT1: aprobada, con folio asignado
        $items1 = [
            ['un_type_id' => $rep->id,  'description' => 'Parachoques Trasero — Reparación',             'price_workshop' => 100000, 'price_authorized' => 95000,  'price_real' => 80000,  'is_approved' => true, 'is_salvage' => false],
            ['un_type_id' => $pint->id, 'description' => 'Parachoques Trasero — Pintura',                'price_workshop' =>  67800, 'price_authorized' => 65000,  'price_real' => 50000,  'is_approved' => true, 'is_salvage' => false],
            ['un_type_id' => $dm->id,   'description' => 'Parachoques Trasero — Desmontar/Montar',       'price_workshop' =>  16000, 'price_authorized' => 16000,  'price_real' => 10000,  'is_approved' => true, 'is_salvage' => false],
            ['un_type_id' => $rep->id,  'description' => 'Guardafango Plástico Trasero Der. — Reparar',  'price_workshop' =>  55000, 'price_authorized' => 50000,  'price_real' => 35000,  'is_approved' => true, 'is_salvage' => false],
            ['un_type_id' => $pint->id, 'description' => 'Guardafango Plástico Trasero Der. — Pintura',  'price_workshop' =>  12000, 'price_authorized' => 12000,  'price_real' =>  8000,  'is_approved' => true, 'is_salvage' => false],
            ['un_type_id' => $dm->id,   'description' => 'Guardafango Plástico Trasero Der. — D/M',      'price_workshop' =>   3500, 'price_authorized' =>  3500,  'price_real' =>  2000,  'is_approved' => false, 'is_salvage' => false],
        ];
        $netoWorkshop1 = collect($items1)->sum('price_workshop');
        $netoAuth1     = collect($items1)->where('is_approved', true)->sum('price_authorized');
        $netoReal1     = collect($items1)->sum('price_real');
        $tax1          = round($netoAuth1 * 0.19);

        $wo1 = WorkOrder::create([
            'folio'                => '1423',
            'date'                 => Carbon::parse('2026-01-05'),
            'status'               => 'approved',
            'vehicle_id'           => $vehicle1->id,
            'client_id'            => $client1->id,
            'insurance_company_id' => $cardif->id,
            'liquidator_id'        => $liquidator1->id,
            'total_workshop'       => $netoWorkshop1,
            'total_authorized'     => $netoAuth1,
            'total_real_cost'      => $netoReal1,
            'tax_amount'           => $tax1,
            'total_amount'         => $netoAuth1 + $tax1,
            'notes'                => 'Reparación de parachoques trasero según presupuesto original.',
        ]);
        $this->insertItems($wo1->id, $items1);

        // Timeline events for OT1
        WorkOrderEvent::create(['work_order_id' => $wo1->id, 'event_type' => 'intake', 'description' => 'OT creada', 'occurred_at' => Carbon::parse('2026-01-05 09:00')]);
        WorkOrderEvent::create(['work_order_id' => $wo1->id, 'event_type' => 'status_change', 'description' => 'Estado cambiado de intake a budget_sent', 'occurred_at' => Carbon::parse('2026-01-05 10:30')]);
        WorkOrderEvent::create(['work_order_id' => $wo1->id, 'event_type' => 'status_change', 'description' => 'Estado cambiado de budget_sent a approved', 'occurred_at' => Carbon::parse('2026-01-06 14:00')]);

        // OT2: ingreso sin folio
        $items2 = [
            ['un_type_id' => $pint->id, 'description' => 'Revisión General De Pintura', 'price_workshop' => 150000, 'price_authorized' => 0, 'price_real' => 0, 'is_approved' => true, 'is_salvage' => false],
            ['un_type_id' => $mat->id,  'description' => 'Material De Preparación',     'price_workshop' =>  25000, 'price_authorized' => 0, 'price_real' => 0, 'is_approved' => true, 'is_salvage' => false],
        ];
        $netoWorkshop2 = collect($items2)->sum('price_workshop');
        $tax2 = round($netoWorkshop2 * 0.19);

        $wo2 = WorkOrder::create([
            'folio'          => null,
            'date'           => Carbon::now(),
            'status'         => 'intake',
            'vehicle_id'     => $vehicle2->id,
            'client_id'      => $client2->id,
            'total_workshop' => $netoWorkshop2,
            'tax_amount'     => $tax2,
            'total_amount'   => $netoWorkshop2 + $tax2,
            'notes'          => 'Revisión inicial por falla de pintura.',
        ]);
        $this->insertItems($wo2->id, $items2);
        WorkOrderEvent::create(['work_order_id' => $wo2->id, 'event_type' => 'intake', 'description' => 'OT creada', 'occurred_at' => now()]);

        // 7. Set folio counter
        Company::current()->update(['folio_counter' => 1424, 'ot_folio_counter' => 1424]);
    }

    private function insertItems(int $workOrderId, array $items): void
    {
        $now  = now();
        $rows = array_map(fn($i) => [
            'work_order_id'    => $workOrderId,
            'un_type_id'       => $i['un_type_id'],
            'description'      => $i['description'],
            'price_workshop'   => $i['price_workshop'],
            'price_authorized' => $i['price_authorized'],
            'price_real'       => $i['price_real'],
            'is_approved'      => $i['is_approved'],
            'is_salvage'       => $i['is_salvage'],
            'created_at'       => $now,
            'updated_at'       => $now,
        ], $items);

        WorkOrderItem::insert($rows);
    }
}
