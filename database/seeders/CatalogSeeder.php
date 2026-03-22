<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Part;
use App\Models\ServiceItem;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedParts();
        $this->seedServiceItems();
        $this->seedVehicleBrands();
    }

    private function seedParts(): void
    {
        if (Part::exists()) {
            return;
        }

        $parts = [
            // Carrocería
            'Carrocería' => [
                'Parachoques Delantero', 'Parachoques Trasero', 'Guardafango Delantero Izquierdo',
                'Guardafango Delantero Derecho', 'Guardafango Trasero Izquierdo', 'Guardafango Trasero Derecho',
                'Capó', 'Maleta / Portalón', 'Techo', 'Puerta Delantera Izquierda', 'Puerta Delantera Derecha',
                'Puerta Trasera Izquierda', 'Puerta Trasera Derecha', 'Panel Lateral Izquierdo',
                'Panel Lateral Derecho', 'Zócalo / Estribo Izquierdo', 'Zócalo / Estribo Derecho',
                'Pilar A Izquierdo', 'Pilar A Derecho', 'Pilar B Izquierdo', 'Pilar B Derecho',
                'Pilar C Izquierdo', 'Pilar C Derecho', 'Marco De Puerta', 'Piso / Bandeja',
                'Travesaño Delantero', 'Travesaño Trasero', 'Rejilla Delantera', 'Spoiler Delantero',
                'Spoiler Trasero', 'Difusor Trasero', 'Paso De Rueda Delantero', 'Paso De Rueda Trasero',
            ],
            // Vidrios
            'Vidrios' => [
                'Parabrisas', 'Vidrio Trasero (Luneta)', 'Vidrio Puerta Delantera Izquierda',
                'Vidrio Puerta Delantera Derecha', 'Vidrio Puerta Trasera Izquierda',
                'Vidrio Puerta Trasera Derecha', 'Vidrio Lateral Fijo (Costado)',
                'Vidrio De Techo (Sunroof)', 'Vidrio Triangular De Puerta',
            ],
            // Luces
            'Luces' => [
                'Faro Delantero Izquierdo', 'Faro Delantero Derecho', 'Faro Trasero Izquierdo',
                'Faro Trasero Derecho', 'Neblinero Delantero Izquierdo', 'Neblinero Delantero Derecho',
                'Neblinero Trasero', 'Luz De Retroceso', 'Tercera Luz De Freno',
                'Luz De Patente', 'Luz Diurna (DRL) Izquierda', 'Luz Diurna (DRL) Derecha',
                'Intermitente Lateral', 'Luz De Espejo Retrovisor',
            ],
            // Espejos y manillas
            'Espejos Y Manillas' => [
                'Espejo Retrovisor Izquierdo', 'Espejo Retrovisor Derecho', 'Espejo Interior',
                'Manilla Exterior Delantera Izquierda', 'Manilla Exterior Delantera Derecha',
                'Manilla Exterior Trasera Izquierda', 'Manilla Exterior Trasera Derecha',
                'Manilla Interior Puerta',
            ],
            // Molduras y accesorios
            'Molduras Y Accesorios' => [
                'Moldura Lateral De Puerta', 'Moldura De Parachoques', 'Moldura De Guardafango',
                'Emblema / Logo Marca', 'Emblema Modelo', 'Pisadera / Estribo Lateral',
                'Barra De Techo', 'Antena', 'Deflector De Aire (Visera)',
                'Protector De Carter', 'Guardabarros (Mudflap)',
            ],
            // Estructura y chasis
            'Estructura Y Chasis' => [
                'Chasis / Larguero', 'Subchasis Delantero (Cuna Motor)', 'Subchasis Trasero',
                'Soporte De Radiador (Frente)', 'Soporte De Motor', 'Soporte De Caja',
                'Barra Estabilizadora Delantera', 'Barra Estabilizadora Trasera',
            ],
            // Interior
            'Interior' => [
                'Tapizado Puerta Delantera', 'Tapizado Puerta Trasera', 'Tablero / Dashboard',
                'Consola Central', 'Guantera', 'Volante', 'Palanca De Cambios',
                'Asiento Delantero', 'Asiento Trasero', 'Cinturón De Seguridad',
                'Airbag Frontal', 'Airbag Lateral', 'Airbag De Cortina',
                'Cielo Interior (Headliner)', 'Alfombra De Piso', 'Parasol',
            ],
            // Mecánica
            'Mecánica' => [
                'Radiador', 'Condensador A/C', 'Intercooler', 'Compresor A/C',
                'Alternador', 'Motor De Partida', 'Batería', 'Bomba De Agua',
                'Bomba De Dirección', 'Cremallera De Dirección', 'Caja De Cambios',
                'Embrague (Kit Completo)', 'Amortiguador Delantero', 'Amortiguador Trasero',
                'Disco De Freno Delantero', 'Disco De Freno Trasero', 'Pastillas De Freno',
                'Catalizador', 'Tubo De Escape', 'Silenciador',
            ],
            // Suspensión
            'Suspensión' => [
                'Brazo De Suspensión Inferior', 'Brazo De Suspensión Superior',
                'Rótula De Suspensión', 'Terminal De Dirección', 'Bujes De Suspensión',
                'Espiral / Resorte Delantero', 'Espiral / Resorte Trasero',
                'Bieleta Estabilizadora',
            ],
            // Ruedas
            'Ruedas' => [
                'Llanta De Aleación', 'Llanta De Acero', 'Neumático',
                'Tapa Centro De Llanta', 'Perno De Rueda', 'Tuerca De Seguridad',
            ],
            // Eléctrico
            'Eléctrico Y Sensores' => [
                'Sensor De Estacionamiento', 'Cámara De Retroceso', 'Cámara Delantera',
                'Sensor ADAS / Radar Frontal', 'Sensor De Punto Ciego',
                'Motor Alzavidrio', 'Motor Limpia Parabrisas', 'Bocina / Claxon',
                'Radio / Sistema Multimedia', 'Parlante / Altavoz',
            ],
        ];

        foreach ($parts as $category => $names) {
            foreach ($names as $name) {
                Part::create(['name' => $name, 'category' => $category, 'active' => true]);
            }
        }
    }

    private function seedServiceItems(): void
    {
        if (ServiceItem::exists()) {
            return;
        }

        $items = [
            // ─── Servicios de detailing ─────────────────────────────
            ['code' => 'DET-001', 'description' => 'Lavado Exterior Completo', 'type' => 'mano_obra', 'default_price' => 15000],
            ['code' => 'DET-002', 'description' => 'Lavado Interior Y Exterior', 'type' => 'mano_obra', 'default_price' => 25000],
            ['code' => 'DET-003', 'description' => 'Detailing Completo (Interior + Exterior)', 'type' => 'mano_obra', 'default_price' => 120000],
            ['code' => 'DET-004', 'description' => 'Pulido Y Abrillantado De Pintura', 'type' => 'mano_obra', 'default_price' => 85000],
            ['code' => 'DET-005', 'description' => 'Encerado Premium', 'type' => 'mano_obra', 'default_price' => 45000],
            ['code' => 'DET-006', 'description' => 'Tratamiento Cerámico', 'type' => 'mano_obra', 'default_price' => 250000],
            ['code' => 'DET-007', 'description' => 'Limpieza De Tapicería', 'type' => 'mano_obra', 'default_price' => 55000],
            ['code' => 'DET-008', 'description' => 'Limpieza De Motor', 'type' => 'mano_obra', 'default_price' => 35000],
            ['code' => 'DET-009', 'description' => 'Restauración De Faros', 'type' => 'mano_obra', 'default_price' => 25000],
            ['code' => 'DET-010', 'description' => 'Descontaminado De Pintura', 'type' => 'mano_obra', 'default_price' => 45000],

            // ─── Productos y materiales ─────────────────────────────
            ['code' => 'PROD-001', 'description' => 'Material De Pintura Base', 'type' => 'repuesto', 'default_price' => 35000],
            ['code' => 'PROD-002', 'description' => 'Material De Preparación', 'type' => 'repuesto', 'default_price' => 25000],
            ['code' => 'PROD-003', 'description' => 'Barniz / Clear Coat', 'type' => 'repuesto', 'default_price' => 28000],
            ['code' => 'PROD-004', 'description' => 'Masilla Poliéster', 'type' => 'repuesto', 'default_price' => 12000],
            ['code' => 'PROD-005', 'description' => 'Primer / Anticorrosivo', 'type' => 'repuesto', 'default_price' => 15000],
            ['code' => 'PROD-006', 'description' => 'Lija Y Abrasivos', 'type' => 'repuesto', 'default_price' => 8000],
            ['code' => 'PROD-007', 'description' => 'Cinta De Enmascarar', 'type' => 'repuesto', 'default_price' => 5000],
            ['code' => 'PROD-008', 'description' => 'Adhesivo Estructural', 'type' => 'repuesto', 'default_price' => 18000],
            ['code' => 'PROD-009', 'description' => 'Sellador De Costura', 'type' => 'repuesto', 'default_price' => 12000],
            ['code' => 'PROD-010', 'description' => 'Film Protector PPF (Por Metro)', 'type' => 'repuesto', 'default_price' => 65000],

            // ─── Servicios mecánicos ────────────────────────────────
            ['code' => 'MEC-001', 'description' => 'Alineación Y Balanceo', 'type' => 'mano_obra', 'default_price' => 35000],
            ['code' => 'MEC-002', 'description' => 'Diagnóstico Electrónico (Scanner)', 'type' => 'mano_obra', 'default_price' => 25000],
            ['code' => 'MEC-003', 'description' => 'Calibración Sensores ADAS', 'type' => 'mano_obra', 'default_price' => 85000],
            ['code' => 'MEC-004', 'description' => 'Recarga Aire Acondicionado', 'type' => 'mano_obra', 'default_price' => 45000],
            ['code' => 'MEC-005', 'description' => 'Revisión Pre-Entrega', 'type' => 'mano_obra', 'default_price' => 15000],
            ['code' => 'MEC-006', 'description' => 'Grúa / Transporte De Vehículo', 'type' => 'mano_obra', 'default_price' => 65000],
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
