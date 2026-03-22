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
        if (InsuranceCompany::exists()) {
            return;
        }

        $rep  = UnType::where('code', 'REP')->first();
        $pint = UnType::where('code', 'PINT')->first();
        $dm   = UnType::where('code', 'D/M')->first();
        $cam  = UnType::where('code', 'C')->first();
        $mat  = UnType::where('code', 'MAT')->first();

        // ── Aseguradoras ────────────────────────────────────────
        $cardif   = InsuranceCompany::create(['name' => 'Cardif']);
        $bci      = InsuranceCompany::create(['name' => 'BCI Seguros']);
        $liberty  = InsuranceCompany::create(['name' => 'Liberty Seguros']);
        $mapfre   = InsuranceCompany::create(['name' => 'Mapfre']);
        $hdi      = InsuranceCompany::create(['name' => 'HDI Seguros']);

        // ── Liquidadores ────────────────────────────────────────
        $liq1 = Liquidator::create(['name' => 'Juan Perez',       'insurance_company_id' => $cardif->id,  'phone' => '+56912345678', 'email' => 'juan.perez@cardif.cl']);
        $liq2 = Liquidator::create(['name' => 'Andrea Muñoz',     'insurance_company_id' => $bci->id,     'phone' => '+56998877665', 'email' => 'andrea.munoz@bci.cl']);
        $liq3 = Liquidator::create(['name' => 'Roberto Soto',     'insurance_company_id' => $liberty->id, 'phone' => '+56977665544', 'email' => 'roberto.soto@liberty.cl']);
        $liq4 = Liquidator::create(['name' => 'Carolina Vega',    'insurance_company_id' => $mapfre->id,  'phone' => '+56955443322', 'email' => 'carolina.vega@mapfre.cl']);

        // ── Etiquetas ───────────────────────────────────────────
        $tagUrg  = Tag::create(['name' => 'Urgente',               'slug' => 'urgente',               'color' => '#dc2626']);
        $tagPend = Tag::create(['name' => 'Pendiente de Repuesto', 'slug' => 'pendiente-de-repuesto', 'color' => '#d97706']);
        $tagRein = Tag::create(['name' => 'Re-inspección',         'slug' => 're-inspeccion',         'color' => '#7c3aed']);
        $tagVip  = Tag::create(['name' => 'Cliente VIP',           'slug' => 'cliente-vip',           'color' => '#0891b2']);
        $tagGrt  = Tag::create(['name' => 'Garantía',              'slug' => 'garantia',              'color' => '#059669']);

        // ── Clientes ────────────────────────────────────────────
        $clients = [];
        $clientData = [
            ['rut_dni' => '12.345.678-9', 'name' => 'Nelson Edgardo Locer',     'phone' => '+56987654321', 'email' => 'nelson@example.com',      'address' => 'Juan Enrique Lira 3580, Viña del Mar'],
            ['rut_dni' => '15.678.901-2', 'name' => 'María José García',        'phone' => '+56911223344', 'email' => 'mariajose@example.com',    'address' => 'Av. Libertad 1020, Viña del Mar'],
            ['rut_dni' => '18.234.567-8', 'name' => 'Carlos Andrés Mendoza',    'phone' => '+56933445566', 'email' => 'carlos.mendoza@gmail.com', 'address' => '15 Norte 820, Viña del Mar'],
            ['rut_dni' => '10.987.654-3', 'name' => 'Patricia Alejandra Rojas', 'phone' => '+56944556677', 'email' => 'patricia.rojas@gmail.com', 'address' => 'Av. Valparaíso 350, Viña del Mar'],
            ['rut_dni' => '16.543.210-K', 'name' => 'Fernando Ignacio Torres',  'phone' => '+56955667788', 'email' => 'fernando.torres@gmail.com','address' => 'Av. España 1560, Valparaíso'],
            ['rut_dni' => '14.321.098-7', 'name' => 'Claudia Beatriz Sánchez',  'phone' => '+56966778899', 'email' => 'claudia.sanchez@gmail.com','address' => 'Calle Blanco 950, Valparaíso'],
            ['rut_dni' => '19.876.543-2', 'name' => 'Rodrigo Esteban Vargas',   'phone' => '+56977889900', 'email' => 'rodrigo.vargas@gmail.com', 'address' => 'Av. Alemania 4200, Valparaíso'],
            ['rut_dni' => '11.222.333-4', 'name' => 'Empresa Transportes Ltda', 'phone' => '+56922334455', 'email' => 'transportes@empresa.cl',   'address' => 'Ruta 68 Km 12, Quilpué'],
        ];
        foreach ($clientData as $c) {
            $clients[] = Client::create($c);
        }

        // ── Vehículos ───────────────────────────────────────────
        $vehicles = [];
        $vehicleData = [
            ['plate' => 'GFGR60', 'brand' => 'Kia',        'model' => 'Carens',       'year' => 2018, 'color' => 'Plateado',   'vin' => 'KNAFX412BCDS123456', 'km' => 85593,  'client' => 0],
            ['plate' => 'ABCD12', 'brand' => 'Hyundai',     'model' => 'Tucson',       'year' => 2022, 'color' => 'Blanco',     'vin' => 'HNDYX9988AA112233', 'km' => 15200,  'client' => 1],
            ['plate' => 'RRTT55', 'brand' => 'Toyota',      'model' => 'Corolla',      'year' => 2021, 'color' => 'Negro',      'vin' => 'JTDBR3FE1MA123456', 'km' => 42300,  'client' => 2],
            ['plate' => 'BBCC33', 'brand' => 'Chevrolet',   'model' => 'Tracker',      'year' => 2023, 'color' => 'Rojo',       'vin' => 'CHL5R1K72NS123456', 'km' => 8700,   'client' => 3],
            ['plate' => 'FFGG77', 'brand' => 'Mazda',       'model' => 'CX-5',         'year' => 2020, 'color' => 'Gris Oscuro','vin' => 'JM3KFBDM3L0123456', 'km' => 65400, 'client' => 4],
            ['plate' => 'HHKK99', 'brand' => 'Suzuki',      'model' => 'Vitara',       'year' => 2019, 'color' => 'Azul',       'vin' => 'JSAALY416W2123456', 'km' => 72100, 'client' => 5],
            ['plate' => 'LLMM44', 'brand' => 'Nissan',      'model' => 'Kicks',        'year' => 2024, 'color' => 'Blanco',     'vin' => '3N1CP5CU5PL123456', 'km' => 3200,  'client' => 6],
            ['plate' => 'PPQQ88', 'brand' => 'Ford',        'model' => 'Ranger',       'year' => 2021, 'color' => 'Gris Plata', 'vin' => 'MNAUMFF50MW123456', 'km' => 48900, 'client' => 7],
            ['plate' => 'SSTT22', 'brand' => 'Volkswagen',  'model' => 'T-Cross',      'year' => 2023, 'color' => 'Naranja',    'vin' => '9BWRB45U3PT123456', 'km' => 12500, 'client' => 2],
            ['plate' => 'XXZZ66', 'brand' => 'MG',          'model' => 'ZS',           'year' => 2022, 'color' => 'Blanco',     'vin' => 'LSJW26R93NS123456', 'km' => 28700, 'client' => 4],
        ];
        foreach ($vehicleData as $v) {
            $vehicles[] = Vehicle::create([
                'license_plate' => $v['plate'], 'brand' => $v['brand'], 'model' => $v['model'],
                'year' => $v['year'], 'color' => $v['color'], 'vin_chassis' => $v['vin'],
                'odometer' => $v['km'], 'client_id' => $clients[$v['client']]->id,
            ]);
        }

        // ── Órdenes de Trabajo ──────────────────────────────────
        $folio = 1420;

        // OT1: Facturada (completada hace 2 meses)
        $folio++;
        $wo = $this->createOT($folio, $vehicles[0], $clients[0], 'invoiced', Carbon::now()->subDays(60), $cardif, $liq1, [
            [$rep->id, 'Parachoques Trasero — Reparación', 100000, 95000, 80000],
            [$pint->id, 'Parachoques Trasero — Pintura', 67800, 65000, 50000],
            [$dm->id, 'Parachoques Trasero — D/M', 16000, 16000, 10000],
        ], '00456');
        $wo->tags()->attach([$tagVip->id]);

        // OT2: Facturada (mes pasado)
        $folio++;
        $wo = $this->createOT($folio, $vehicles[2], $clients[2], 'invoiced', Carbon::now()->subDays(35), $liberty, $liq3, [
            [$rep->id, 'Puerta Delantera Izquierda — Reparación', 95000, 90000, 70000],
            [$pint->id, 'Puerta Delantera Izquierda — Pintura', 85000, 80000, 55000],
            [$dm->id, 'Puerta Delantera Izquierda — D/M', 25000, 25000, 15000],
            [$cam->id, 'Espejo Retrovisor Izquierdo — Cambio', 85000, 85000, 45000],
        ], '00512');

        // OT3: Aprobada, en proceso (hace 10 días)
        $folio++;
        $this->createOT($folio, $vehicles[1], $clients[1], 'approved', Carbon::now()->subDays(10), $bci, $liq2, [
            [$rep->id, 'Guardafango Delantero Derecho — Reparación', 65000, 60000, 45000],
            [$pint->id, 'Guardafango Delantero Derecho — Pintura', 67800, 65000, 48000],
            [$dm->id, 'Guardafango Delantero Derecho — D/M', 12000, 12000, 8000],
            [$pint->id, 'Difuminado Puerta Delantera — Pintura', 35000, 35000, 25000],
        ]);

        // OT4: En Reparación (hace 5 días)
        $folio++;
        $wo = $this->createOT($folio, $vehicles[3], $clients[3], 'in_repair', Carbon::now()->subDays(5), $mapfre, $liq4, [
            [$rep->id, 'Capó — Reparación', 110000, 105000, 80000],
            [$pint->id, 'Capó — Pintura', 120000, 115000, 85000],
            [$dm->id, 'Capó — D/M', 15000, 15000, 10000],
            [$cam->id, 'Rejilla Delantera — Cambio', 65000, 65000, 35000],
            [$cam->id, 'Faro Delantero Derecho — Cambio', 150000, 145000, 90000],
        ]);
        $wo->tags()->attach([$tagUrg->id]);

        // OT5: Esperando Repuestos (hace 8 días)
        $folio++;
        $wo = $this->createOT($folio, $vehicles[4], $clients[4], 'waiting_parts', Carbon::now()->subDays(8), $hdi, null, [
            [$cam->id, 'Vidrio Parabrisas — Cambio', 180000, 175000, 120000],
            [$dm->id, 'Vidrio Parabrisas — D/M', 35000, 35000, 20000],
            [$rep->id, 'Pilar A Derecho — Reparación', 80000, 75000, 55000],
            [$pint->id, 'Pilar A Derecho — Pintura', 45000, 42000, 30000],
        ]);
        $wo->tags()->attach([$tagPend->id]);

        // OT6: Presupuesto Enviado (hace 3 días)
        $folio++;
        $this->createOT($folio, $vehicles[5], $clients[5], 'budget_sent', Carbon::now()->subDays(3), null, null, [
            [$rep->id, 'Panel Lateral Izquierdo — Reparación', 90000, 85000, 65000],
            [$pint->id, 'Panel Lateral Izquierdo — Pintura', 95000, 90000, 70000],
            [$dm->id, 'Panel Lateral Izquierdo — D/M', 18000, 18000, 12000],
            [$rep->id, 'Zócalo Izquierdo — Reparación', 55000, 50000, 35000],
            [$pint->id, 'Zócalo Izquierdo — Pintura', 45000, 42000, 30000],
        ]);

        // OT7: Ingreso reciente (ayer)
        $this->createOT(null, $vehicles[6], $clients[6], 'intake', Carbon::now()->subDays(1), $cardif, $liq1, [
            [$rep->id, 'Parachoques Delantero — Reparación', 85000, 0, 0],
            [$pint->id, 'Parachoques Delantero — Pintura', 95000, 0, 0],
            [$dm->id, 'Parachoques Delantero — D/M', 16000, 0, 0],
            [$cam->id, 'Neblinero Delantero Izquierdo — Cambio', 45000, 0, 0],
        ]);

        // OT8: Completado, listo para entregar
        $folio++;
        $this->createOT($folio, $vehicles[7], $clients[7], 'completed', Carbon::now()->subDays(15), $liberty, $liq3, [
            [$rep->id, 'Maleta / Portalón — Reparación', 95000, 90000, 68000],
            [$pint->id, 'Maleta / Portalón — Pintura', 95000, 90000, 65000],
            [$dm->id, 'Maleta / Portalón — D/M', 18000, 18000, 12000],
            [$cam->id, 'Faro Trasero Derecho — Cambio', 95000, 90000, 55000],
        ]);

        // OT9: En Reparación (particular, sin seguro)
        $folio++;
        $wo = $this->createOT($folio, $vehicles[8], $clients[2], 'in_repair', Carbon::now()->subDays(4), null, null, [
            [$pint->id, 'Pintura Completa Capó', 120000, 120000, 85000],
            [$pint->id, 'Pintura Guardafango Delantero Izquierdo', 67800, 67800, 48000],
            [$pint->id, 'Difuminado Puerta Delantera Izquierda', 35000, 35000, 25000],
            [$mat->id, 'Material De Pintura Base', 35000, 35000, 35000],
            [$mat->id, 'Material De Barniz', 28000, 28000, 28000],
        ]);
        $wo->tags()->attach([$tagRein->id]);

        // OT10: Ingreso hoy
        $this->createOT(null, $vehicles[9], $clients[4], 'intake', Carbon::now(), $bci, $liq2, [
            [$rep->id, 'Puerta Trasera Derecha — Reparación', 95000, 0, 0],
            [$pint->id, 'Puerta Trasera Derecha — Pintura', 85000, 0, 0],
            [$dm->id, 'Puerta Trasera Derecha — D/M', 25000, 0, 0],
        ]);

        // Set folio counter
        Company::current()->update(['folio_counter' => $folio + 1, 'ot_folio_counter' => $folio + 1]);
    }

    private function createOT(?int $folio, Vehicle $vehicle, Client $client, string $status, Carbon $date, ?InsuranceCompany $insurance, ?Liquidator $liquidator, array $items, ?string $invoiceNumber = null): WorkOrder
    {
        $rep  = UnType::where('code', 'REP')->first();
        $rows = [];
        foreach ($items as [$unTypeId, $desc, $pw, $pa, $pr]) {
            $rows[] = ['un_type_id' => $unTypeId, 'description' => $desc, 'price_workshop' => $pw, 'price_authorized' => $pa, 'price_real' => $pr, 'is_approved' => $pa > 0, 'is_salvage' => false];
        }

        $netoW = collect($rows)->sum('price_workshop');
        $netoA = collect($rows)->where('is_approved', true)->sum('price_authorized');
        $netoR = collect($rows)->sum('price_real');
        $tax   = round($netoA * 0.19);

        $wo = WorkOrder::create([
            'folio'                => $folio ? str_pad($folio, 4, '0', STR_PAD_LEFT) : null,
            'invoice_number'       => $invoiceNumber,
            'date'                 => $date,
            'status'               => $status,
            'vehicle_id'           => $vehicle->id,
            'client_id'            => $client->id,
            'insurance_company_id' => $insurance?->id,
            'liquidator_id'        => $liquidator?->id,
            'total_workshop'       => $netoW,
            'total_authorized'     => $netoA,
            'total_real_cost'      => $netoR,
            'tax_amount'           => $tax,
            'total_amount'         => $netoA + $tax,
        ]);
        $this->insertItems($wo->id, $rows);

        // Timeline
        WorkOrderEvent::create(['work_order_id' => $wo->id, 'event_type' => 'intake', 'description' => 'Orden de trabajo creada', 'occurred_at' => $date]);
        $transitions = ['intake' => [], 'budget_sent' => ['Ingreso','Presupuesto Enviado'], 'approved' => ['Presupuesto Enviado','Aprobado'], 'waiting_parts' => ['Aprobado','Esperando Repuestos'], 'in_repair' => ['Esperando Repuestos','En Reparación'], 'completed' => ['En Reparación','Completado'], 'delivered' => ['Completado','Entregado'], 'invoiced' => ['Entregado','Facturado']];
        $statusFlow = ['intake','budget_sent','approved','waiting_parts','in_repair','completed','delivered','invoiced'];
        $targetIdx = array_search($status, $statusFlow);
        for ($i = 1; $i <= $targetIdx; $i++) {
            $prev = $statusFlow[$i-1];
            $curr = $statusFlow[$i];
            $labels = ['intake'=>'Ingreso','budget_sent'=>'Presupuesto Enviado','approved'=>'Aprobado','waiting_parts'=>'Esperando Repuestos','in_repair'=>'En Reparación','completed'=>'Completado','delivered'=>'Entregado','invoiced'=>'Facturado'];
            WorkOrderEvent::create([
                'work_order_id' => $wo->id,
                'event_type' => 'status_change',
                'description' => "Estado cambiado de {$labels[$prev]} a {$labels[$curr]}",
                'occurred_at' => $date->copy()->addHours($i),
            ]);
        }

        return $wo;
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
