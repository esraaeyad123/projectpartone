<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PriceList;

class PriceListsSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['service_id' => '102218', 'name' => 'Monitoring of fresh concrete', 'method' => 'ASTM C39', 'unit' => 'NO.', 'price' => 22.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => '102220', 'name' => 'Sampling of fresh concrete', 'method' => 'ASTM C172', 'unit' => 'NO.', 'price' => 22.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => '102455', 'name' => 'اختبار الخبوط', 'method' => 'ASTM C143', 'unit' => 'NO.', 'price' => 100.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => '102459', 'name' => 'اختبار مقاومة البري', 'method' => 'ASTM C944', 'unit' => 'NO.', 'price' => 185.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => '102460', 'name' => 'اختبار مقاومة الصدم', 'method' => 'ASTM C1138', 'unit' => 'Each', 'price' => 200.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => '102462', 'name' => 'الإحالة باستخدام كبريتات الصوديوم والمغنيسيوم', 'method' => 'ASTM C88', 'unit' => 'Each', 'price' => 200.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => '102467', 'name' => 'الاختصاص', 'method' => 'N/A', 'unit' => 'NO.', 'price' => 80.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => '102461', 'name' => 'النموذج العيني', 'method' => 'N/A', 'unit' => 'NO.', 'price' => 130.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => '102469', 'name' => 'التسربات العضوية', 'method' => 'N/A', 'unit' => 'NO.', 'price' => 180.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => '102468', 'name' => 'المعادن الرماد', 'method' => 'N/A', 'unit' => 'Each', 'price' => 200.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => '102466', 'name' => 'الوزن النوعي', 'method' => 'N/A', 'unit' => 'NO.', 'price' => 75.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => 'LIMS-001', 'name' => 'Concrete Compressive Strength', 'method' => 'ASTM C39', 'unit' => 'NO.', 'price' => 150.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => 'LIMS-002', 'name' => 'Cement Fineness Test', 'method' => 'ASTM C204', 'unit' => 'NO.', 'price' => 10.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => 'LIMS-003', 'name' => 'Soil Proctor Test', 'method' => 'ASTM D698', 'unit' => 'Each', 'price' => 25.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => 'LIMS-004', 'name' => 'Asphalt Content Test', 'method' => 'ASTM D2172', 'unit' => 'NO.', 'price' => 28.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => 'LIMS-005', 'name' => 'Aggregate Sieve Analysis', 'method' => 'ASTM C136', 'unit' => 'NO.', 'price' => 10.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => 'LIMS-006', 'name' => 'Water Absorption Test', 'method' => 'ASTM C127', 'unit' => 'NO.', 'price' => 200.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => 'LIMS-007', 'name' => 'Density of Soil', 'method' => 'ASTM D2937', 'unit' => 'NO.', 'price' => 10.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => 'LIMS-008', 'name' => 'Concrete Mix Design', 'method' => 'ACI 211.1', 'unit' => 'Each', 'price' => 120.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => 'LIMS-009', 'name' => 'Rebar Tensile Test', 'method' => 'ASTM A370', 'unit' => 'NO.', 'price' => 10.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => 'LIMS-010', 'name' => 'Block Compressive Strength', 'method' => 'ASTM C140', 'unit' => 'NO.', 'price' => 100.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => 'LIMS-011', 'name' => 'Chemical Analysis of Water', 'method' => 'APHA 4500', 'unit' => 'NO.', 'price' => 300.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => 'LIMS-012', 'name' => 'Bitumen ***** Test', 'method' => 'ASTM D5', 'unit' => 'Each', 'price' => 35.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => 'LIMS-013', 'name' => 'Field Density Test (Sand Cone)', 'method' => 'ASTM D1556', 'unit' => 'NO.', 'price' => 40.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => 'LIMS-014', 'name' => 'Pile Load Test', 'method' => 'ASTM D1143', 'unit' => 'NO.', 'price' => 1500.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
            ['service_id' => 'LIMS-015', 'name' => 'Soil Classification', 'method' => 'ASTM D2487', 'unit' => 'Each', 'price' => 75.00, 'price_only' => false, 'quantity' => 1, 'active' => true],
        ];

        foreach ($data as $item) {
            PriceList::create($item);
        }
    }
}
