<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\InsuranceCompany;
use App\Models\Liquidator;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Insurance Companies
        $cardif = InsuranceCompany::create(['name' => 'Cardif']);
        $bci = InsuranceCompany::create(['name' => 'BCI Seguros']);

        // 2. Create Liquidators
        $liquidator1 = Liquidator::create([
            'name' => 'Juan Perez',
            'insurance_company_id' => $cardif->id,
            'phone' => '+56912345678',
            'email' => 'juan.perez@cardif.cl'
        ]);

        // 3. Create Clients
        $client1 = Client::create([
            'rut_dni' => '12.345.678-9',
            'name' => 'Nelson Edgardo Locer',
            'phone' => '+56987654321',
            'email' => 'nelson@example.com',
            'address' => 'Juan Enrique Lira 3580, Viña del Mar'
        ]);

        $client2 = Client::create([
            'rut_dni' => '15.678.901-2',
            'name' => 'María José García',
            'phone' => '+56911223344',
            'email' => 'mariajose@example.com',
            'address' => 'Av. Libertad 1020, Viña del Mar'
        ]);

        // 4. Create Vehicles
        $vehicle1 = Vehicle::create([
            'license_plate' => 'GFGR-60',
            'brand' => 'Kia',
            'model' => 'Carens',
            'year' => 2018,
            'color' => 'Plateado',
            'vin_chassis' => 'KNAFX412BCDS123456',
            'odometer' => 85593,
            'client_id' => $client1->id
        ]);

        $vehicle2 = Vehicle::create([
            'license_plate' => 'ABCD-12',
            'brand' => 'Hyundai',
            'model' => 'Tucson',
            'year' => 2022,
            'color' => 'Blanco',
            'vin_chassis' => 'HNDYX9988AA112233',
            'odometer' => 15200,
            'client_id' => $client2->id
        ]);

        // 5. Create Quotations
        $quote1 = Quotation::create([
            'folio' => '1423',
            'date' => Carbon::parse('2026-01-05'),
            'status' => 'approved',
            'vehicle_id' => $vehicle1->id,
            'client_id' => $client1->id,
            'insurance_company_id' => $cardif->id,
            'liquidator_id' => $liquidator1->id,
            'tax_amount' => 34922,
            'total_amount' => 218722,
            'notes' => 'Reparación de parachoques trasero según presupuesto original.'
        ]);

        $quote2 = Quotation::create([
            'folio' => '1424',
            'date' => Carbon::now(),
            'status' => 'draft',
            'vehicle_id' => $vehicle2->id,
            'client_id' => $client2->id,
            'tax_amount' => 28500,
            'total_amount' => 178500,
            'notes' => 'Revision inicial por falla de pintura.'
        ]);

        // 6. Add Items (new 5-column schema)
        QuotationItem::create([
            'quotation_id' => $quote1->id,
            'action'       => 'REP',
            'description'  => 'PARACHOQUES TRASERO',
            'repair_price' => 100000,
            'paint_price'  => 67800,
            'dm_price'     => 16000,
            'parts_price'  => 0,
            'other_price'  => 0,
            'is_salvage'   => false,
            'subtotal'     => 183800,
        ]);

        QuotationItem::create([
            'quotation_id' => $quote1->id,
            'action'       => 'REP',
            'description'  => 'GUARDAFANGO PLASTICO TRASERO DERECHO',
            'repair_price' => 55000,
            'paint_price'  => 12000,
            'dm_price'     => 3500,
            'parts_price'  => 0,
            'other_price'  => 0,
            'is_salvage'   => false,
            'subtotal'     => 70500,
        ]);

        QuotationItem::create([
            'quotation_id' => $quote2->id,
            'action'       => 'D/M',
            'description'  => 'REVISIÓN GENERAL DE PINTURA',
            'repair_price' => 0,
            'paint_price'  => 150000,
            'dm_price'     => 0,
            'parts_price'  => 0,
            'other_price'  => 0,
            'is_salvage'   => false,
            'subtotal'     => 150000,
        ]);
    }
}
