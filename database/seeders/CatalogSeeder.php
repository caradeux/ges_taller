<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceItem;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedServiceItems();
        $this->seedVehicleBrands();
    }

    private function seedServiceItems(): void
    {
        if (ServiceItem::exists()) {
            return;
        }

        $items = [
            // ─── Reparación de carrocería ───────────────────────────
            ['code' => 'REP-001', 'description' => 'Reparación Parachoques Delantero', 'type' => 'mano_obra', 'default_price' => 85000],
            ['code' => 'REP-002', 'description' => 'Reparación Parachoques Trasero', 'type' => 'mano_obra', 'default_price' => 85000],
            ['code' => 'REP-003', 'description' => 'Reparación Guardafango Delantero', 'type' => 'mano_obra', 'default_price' => 65000],
            ['code' => 'REP-004', 'description' => 'Reparación Guardafango Trasero', 'type' => 'mano_obra', 'default_price' => 65000],
            ['code' => 'REP-005', 'description' => 'Reparación Puerta Delantera', 'type' => 'mano_obra', 'default_price' => 95000],
            ['code' => 'REP-006', 'description' => 'Reparación Puerta Trasera', 'type' => 'mano_obra', 'default_price' => 95000],
            ['code' => 'REP-007', 'description' => 'Reparación Capó', 'type' => 'mano_obra', 'default_price' => 110000],
            ['code' => 'REP-008', 'description' => 'Reparación Maleta / Portalón', 'type' => 'mano_obra', 'default_price' => 95000],
            ['code' => 'REP-009', 'description' => 'Reparación Techo', 'type' => 'mano_obra', 'default_price' => 120000],
            ['code' => 'REP-010', 'description' => 'Reparación Zócalo / Estribo', 'type' => 'mano_obra', 'default_price' => 55000],
            ['code' => 'REP-011', 'description' => 'Reparación Panel Lateral', 'type' => 'mano_obra', 'default_price' => 90000],
            ['code' => 'REP-012', 'description' => 'Reparación Marco De Puerta', 'type' => 'mano_obra', 'default_price' => 75000],
            ['code' => 'REP-013', 'description' => 'Reparación Pilar A', 'type' => 'mano_obra', 'default_price' => 80000],
            ['code' => 'REP-014', 'description' => 'Reparación Pilar B', 'type' => 'mano_obra', 'default_price' => 85000],
            ['code' => 'REP-015', 'description' => 'Reparación Pilar C', 'type' => 'mano_obra', 'default_price' => 80000],
            ['code' => 'REP-016', 'description' => 'Enderezado De Chasis', 'type' => 'mano_obra', 'default_price' => 250000],
            ['code' => 'REP-017', 'description' => 'Reparación Piso / Bandeja', 'type' => 'mano_obra', 'default_price' => 95000],

            // ─── Pintura ────────────────────────────────────────────
            ['code' => 'PINT-001', 'description' => 'Pintura Parachoques Delantero', 'type' => 'mano_obra', 'default_price' => 95000],
            ['code' => 'PINT-002', 'description' => 'Pintura Parachoques Trasero', 'type' => 'mano_obra', 'default_price' => 95000],
            ['code' => 'PINT-003', 'description' => 'Pintura Guardafango Delantero', 'type' => 'mano_obra', 'default_price' => 67800],
            ['code' => 'PINT-004', 'description' => 'Pintura Guardafango Trasero', 'type' => 'mano_obra', 'default_price' => 67800],
            ['code' => 'PINT-005', 'description' => 'Pintura Puerta Delantera', 'type' => 'mano_obra', 'default_price' => 85000],
            ['code' => 'PINT-006', 'description' => 'Pintura Puerta Trasera', 'type' => 'mano_obra', 'default_price' => 85000],
            ['code' => 'PINT-007', 'description' => 'Pintura Capó', 'type' => 'mano_obra', 'default_price' => 120000],
            ['code' => 'PINT-008', 'description' => 'Pintura Maleta / Portalón', 'type' => 'mano_obra', 'default_price' => 95000],
            ['code' => 'PINT-009', 'description' => 'Pintura Techo', 'type' => 'mano_obra', 'default_price' => 150000],
            ['code' => 'PINT-010', 'description' => 'Pintura Zócalo / Estribo', 'type' => 'mano_obra', 'default_price' => 45000],
            ['code' => 'PINT-011', 'description' => 'Pintura Panel Lateral', 'type' => 'mano_obra', 'default_price' => 95000],
            ['code' => 'PINT-012', 'description' => 'Difuminado / Empalme De Color', 'type' => 'mano_obra', 'default_price' => 35000],
            ['code' => 'PINT-013', 'description' => 'Pintura Espejo Retrovisor', 'type' => 'mano_obra', 'default_price' => 25000],
            ['code' => 'PINT-014', 'description' => 'Pintura Completa Vehículo', 'type' => 'mano_obra', 'default_price' => 850000],

            // ─── Desmontar / Montar ─────────────────────────────────
            ['code' => 'DM-001', 'description' => 'D/M Parachoques Delantero', 'type' => 'mano_obra', 'default_price' => 16000],
            ['code' => 'DM-002', 'description' => 'D/M Parachoques Trasero', 'type' => 'mano_obra', 'default_price' => 16000],
            ['code' => 'DM-003', 'description' => 'D/M Guardafango', 'type' => 'mano_obra', 'default_price' => 12000],
            ['code' => 'DM-004', 'description' => 'D/M Puerta Completa', 'type' => 'mano_obra', 'default_price' => 25000],
            ['code' => 'DM-005', 'description' => 'D/M Capó', 'type' => 'mano_obra', 'default_price' => 15000],
            ['code' => 'DM-006', 'description' => 'D/M Maleta / Portalón', 'type' => 'mano_obra', 'default_price' => 18000],
            ['code' => 'DM-007', 'description' => 'D/M Vidrio Parabrisas', 'type' => 'mano_obra', 'default_price' => 35000],
            ['code' => 'DM-008', 'description' => 'D/M Vidrio Trasero', 'type' => 'mano_obra', 'default_price' => 30000],
            ['code' => 'DM-009', 'description' => 'D/M Faro Delantero', 'type' => 'mano_obra', 'default_price' => 12000],
            ['code' => 'DM-010', 'description' => 'D/M Faro Trasero', 'type' => 'mano_obra', 'default_price' => 10000],
            ['code' => 'DM-011', 'description' => 'D/M Espejo Retrovisor', 'type' => 'mano_obra', 'default_price' => 8000],
            ['code' => 'DM-012', 'description' => 'D/M Molduras Y Emblemas', 'type' => 'mano_obra', 'default_price' => 10000],
            ['code' => 'DM-013', 'description' => 'D/M Manilla De Puerta', 'type' => 'mano_obra', 'default_price' => 8000],
            ['code' => 'DM-014', 'description' => 'D/M Tapizado Interior Puerta', 'type' => 'mano_obra', 'default_price' => 12000],
            ['code' => 'DM-015', 'description' => 'D/M Sistema Eléctrico Puerta', 'type' => 'mano_obra', 'default_price' => 18000],

            // ─── Repuestos / Cambio ─────────────────────────────────
            ['code' => 'CAM-001', 'description' => 'Cambio Parachoques Delantero', 'type' => 'repuesto', 'default_price' => 180000],
            ['code' => 'CAM-002', 'description' => 'Cambio Parachoques Trasero', 'type' => 'repuesto', 'default_price' => 180000],
            ['code' => 'CAM-003', 'description' => 'Cambio Guardafango Delantero', 'type' => 'repuesto', 'default_price' => 120000],
            ['code' => 'CAM-004', 'description' => 'Cambio Guardafango Trasero', 'type' => 'repuesto', 'default_price' => 120000],
            ['code' => 'CAM-005', 'description' => 'Cambio Capó', 'type' => 'repuesto', 'default_price' => 250000],
            ['code' => 'CAM-006', 'description' => 'Cambio Faro Delantero', 'type' => 'repuesto', 'default_price' => 150000],
            ['code' => 'CAM-007', 'description' => 'Cambio Faro Trasero', 'type' => 'repuesto', 'default_price' => 95000],
            ['code' => 'CAM-008', 'description' => 'Cambio Espejo Retrovisor', 'type' => 'repuesto', 'default_price' => 85000],
            ['code' => 'CAM-009', 'description' => 'Cambio Vidrio Parabrisas', 'type' => 'repuesto', 'default_price' => 180000],
            ['code' => 'CAM-010', 'description' => 'Cambio Vidrio Puerta', 'type' => 'repuesto', 'default_price' => 95000],
            ['code' => 'CAM-011', 'description' => 'Cambio Vidrio Trasero', 'type' => 'repuesto', 'default_price' => 150000],
            ['code' => 'CAM-012', 'description' => 'Cambio Moldura De Puerta', 'type' => 'repuesto', 'default_price' => 35000],
            ['code' => 'CAM-013', 'description' => 'Cambio Manilla Exterior', 'type' => 'repuesto', 'default_price' => 25000],
            ['code' => 'CAM-014', 'description' => 'Cambio Rejilla Delantera', 'type' => 'repuesto', 'default_price' => 65000],
            ['code' => 'CAM-015', 'description' => 'Cambio Neblinero', 'type' => 'repuesto', 'default_price' => 45000],
            ['code' => 'CAM-016', 'description' => 'Cambio Pisadera / Estribo', 'type' => 'repuesto', 'default_price' => 75000],
            ['code' => 'CAM-017', 'description' => 'Cambio Emblema / Logo', 'type' => 'repuesto', 'default_price' => 15000],
            ['code' => 'CAM-018', 'description' => 'Cambio Bisagra De Puerta', 'type' => 'repuesto', 'default_price' => 30000],
            ['code' => 'CAM-019', 'description' => 'Cambio Cerradura De Puerta', 'type' => 'repuesto', 'default_price' => 45000],
            ['code' => 'CAM-020', 'description' => 'Cambio Amortiguador Capó', 'type' => 'repuesto', 'default_price' => 18000],

            // ─── Materiales ─────────────────────────────────────────
            ['code' => 'MAT-001', 'description' => 'Material De Pintura Base', 'type' => 'repuesto', 'default_price' => 35000],
            ['code' => 'MAT-002', 'description' => 'Material De Preparación', 'type' => 'repuesto', 'default_price' => 25000],
            ['code' => 'MAT-003', 'description' => 'Material De Barniz', 'type' => 'repuesto', 'default_price' => 28000],
            ['code' => 'MAT-004', 'description' => 'Masilla Poliéster', 'type' => 'repuesto', 'default_price' => 12000],
            ['code' => 'MAT-005', 'description' => 'Primer / Anticorrosivo', 'type' => 'repuesto', 'default_price' => 15000],
            ['code' => 'MAT-006', 'description' => 'Lija Y Abrasivos', 'type' => 'repuesto', 'default_price' => 8000],
            ['code' => 'MAT-007', 'description' => 'Cinta De Enmascarar', 'type' => 'repuesto', 'default_price' => 5000],
            ['code' => 'MAT-008', 'description' => 'Adhesivo Estructural', 'type' => 'repuesto', 'default_price' => 18000],
            ['code' => 'MAT-009', 'description' => 'Sellador De Costura', 'type' => 'repuesto', 'default_price' => 12000],
            ['code' => 'MAT-010', 'description' => 'Pulido Y Abrillantado', 'type' => 'mano_obra', 'default_price' => 45000],

            // ─── Mecánica general ───────────────────────────────────
            ['code' => 'MEC-001', 'description' => 'Alineación Y Balanceo', 'type' => 'mano_obra', 'default_price' => 35000],
            ['code' => 'MEC-002', 'description' => 'Diagnóstico Electrónico', 'type' => 'mano_obra', 'default_price' => 25000],
            ['code' => 'MEC-003', 'description' => 'Calibración Sensores ADAS', 'type' => 'mano_obra', 'default_price' => 85000],
            ['code' => 'MEC-004', 'description' => 'Recarga Aire Acondicionado', 'type' => 'mano_obra', 'default_price' => 45000],
            ['code' => 'MEC-005', 'description' => 'Cambio Radiador', 'type' => 'repuesto', 'default_price' => 180000],
            ['code' => 'MEC-006', 'description' => 'Cambio Condensador A/C', 'type' => 'repuesto', 'default_price' => 150000],
            ['code' => 'MEC-007', 'description' => 'Reparación Sistema Eléctrico', 'type' => 'mano_obra', 'default_price' => 55000],
            ['code' => 'MEC-008', 'description' => 'Cambio Airbag', 'type' => 'repuesto', 'default_price' => 350000],
        ];

        foreach ($items as $item) {
            ServiceItem::create(array_merge($item, ['active' => true]));
        }
    }

    private function seedVehicleBrands(): void
    {
        if (VehicleBrand::exists()) {
            return;
        }

        $brands = [
            'Chevrolet' => ['Spark', 'Sail', 'Onix', 'Tracker', 'Equinox', 'Orlando', 'Captiva', 'Traverse', 'Silverado', 'N300', 'N400'],
            'Hyundai'   => ['Accent', 'Elantra', 'Sonata', 'i10', 'i20', 'i30', 'Tucson', 'Santa Fe', 'Kona', 'Creta', 'Venue', 'Staria', 'Ioniq 5'],
            'Kia'       => ['Morning', 'Rio', 'Cerato', 'Sportage', 'Seltos', 'Sorento', 'Carnival', 'Stonic', 'Niro', 'EV6', 'Picanto', 'K3', 'Carens'],
            'Toyota'    => ['Yaris', 'Corolla', 'Camry', 'RAV4', 'Hilux', 'Fortuner', 'Land Cruiser', 'C-HR', 'Corolla Cross', 'Rush', 'Hiace'],
            'Nissan'    => ['Versa', 'Sentra', 'March', 'Kicks', 'Qashqai', 'X-Trail', 'Navara', 'Frontier', 'Leaf', 'Pathfinder'],
            'Suzuki'    => ['Swift', 'Baleno', 'Vitara', 'S-Cross', 'Jimny', 'Ertiga', 'Alto', 'Celerio', 'Ignis', 'XL7'],
            'Mazda'     => ['Mazda2', 'Mazda3', 'Mazda6', 'CX-3', 'CX-30', 'CX-5', 'CX-9', 'MX-5', 'CX-50'],
            'Mitsubishi' => ['L200', 'ASX', 'Outlander', 'Eclipse Cross', 'Montero Sport', 'Xpander'],
            'Ford'      => ['Fiesta', 'Focus', 'Escape', 'Territory', 'Explorer', 'Ranger', 'Bronco Sport', 'Maverick', 'F-150', 'Transit'],
            'Volkswagen' => ['Gol', 'Polo', 'Virtus', 'Golf', 'Jetta', 'T-Cross', 'Taos', 'Tiguan', 'Amarok', 'ID.4', 'Saveiro', 'Transporter'],
            'Peugeot'   => ['208', '301', '308', '2008', '3008', '5008', 'Partner', 'Rifter', 'Expert', 'Landtrek'],
            'Citroën'   => ['C3', 'C3 Aircross', 'C4 Cactus', 'C5 Aircross', 'Berlingo', 'Jumpy'],
            'Renault'   => ['Kwid', 'Logan', 'Sandero', 'Stepway', 'Duster', 'Captur', 'Koleos', 'Kangoo', 'Master'],
            'Fiat'      => ['Uno', 'Argo', 'Cronos', 'Pulse', 'Fastback', 'Strada', 'Fiorino', 'Ducato', 'Toro'],
            'Honda'     => ['Fit', 'City', 'Civic', 'Accord', 'HR-V', 'CR-V', 'WR-V', 'ZR-V'],
            'Subaru'    => ['Impreza', 'XV', 'Forester', 'Outback', 'WRX', 'Crosstrek', 'Solterra'],
            'MG'        => ['MG3', 'MG5', 'MG ZS', 'MG HS', 'MG RX5', 'MG4', 'Marvel R', 'MG ZS EV'],
            'Chery'     => ['Tiggo 2', 'Tiggo 3', 'Tiggo 4', 'Tiggo 5X', 'Tiggo 7', 'Tiggo 8', 'Arrizo 5', 'iCar 03'],
            'JAC'       => ['S2', 'S3', 'S4', 'S7', 'JS2', 'JS3', 'JS4', 'T6', 'T8', 'E10X', 'Refine'],
            'Great Wall' => ['Haval H2', 'Haval H6', 'Haval Jolion', 'Haval Dargo', 'Poer', 'Ora 03'],
            'BYD'       => ['Dolphin', 'Seal', 'Atto 3', 'Tang', 'Han', 'Song Plus', 'Yuan Plus', 'Shark'],
            'Jeep'      => ['Renegade', 'Compass', 'Cherokee', 'Grand Cherokee', 'Wrangler', 'Gladiator', 'Commander'],
            'BMW'       => ['Serie 1', 'Serie 2', 'Serie 3', 'Serie 5', 'X1', 'X2', 'X3', 'X5', 'iX1', 'i4', 'iX'],
            'Mercedes-Benz' => ['Clase A', 'Clase C', 'Clase E', 'GLA', 'GLB', 'GLC', 'GLE', 'Sprinter', 'Vito', 'EQA', 'EQB'],
            'Audi'      => ['A1', 'A3', 'A4', 'A5', 'Q2', 'Q3', 'Q5', 'Q7', 'Q8', 'e-tron'],
            'Volvo'     => ['XC40', 'XC60', 'XC90', 'S60', 'V40', 'C40', 'EX30', 'EX90'],
            'RAM'       => ['700', '1200', '1500', '2500'],
            'DFSK'      => ['K01', 'K01H', 'K05', 'C35', 'Glory 500', 'Glory 580', 'Seres 3', 'EC35'],
            'Maxus'     => ['T60', 'T90', 'D90', 'V80', 'G10', 'Euniq 5', 'Euniq 6', 'Mifa 9'],
            'Changan'   => ['Alsvin', 'CS15', 'CS35 Plus', 'CS55 Plus', 'CS75 Plus', 'UNI-T', 'UNI-K', 'Hunter'],
        ];

        foreach ($brands as $brandName => $models) {
            $brand = VehicleBrand::create(['name' => $brandName]);
            foreach ($models as $modelName) {
                VehicleModel::create([
                    'vehicle_brand_id' => $brand->id,
                    'name' => $modelName,
                ]);
            }
        }
    }
}
