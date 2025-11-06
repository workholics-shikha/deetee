<?php

namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Endroid\QrCode\Builder\Builder;
use Illuminate\Support\Facades\{Storage, DB};

class ProductMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_masters')->insert(
            [

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'A', 'erp_product' => 'Slitting Cutters', 'erp_nomenclature' => 'SC', 'product_modified_name' => 'Slitting Cutters'],

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'A', 'erp_product' => 'Spacers', 'erp_nomenclature' => 'SP', 'product_modified_name' => 'Spacers'],

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'A', 'erp_product' => 'Rubber Bonded Spacers', 'erp_nomenclature' => 'RSP', 'product_modified_name' => 'Rubber Bonded Spacers'],

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'A', 'erp_product' => 'Rubber Rings',    'erp_nomenclature' => 'RR', 'product_modified_name' => 'Rubber Rings'],

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'A', 'erp_product' => 'Light Weight Spacer',    'erp_nomenclature' => 'LWSP', 'product_modified_name' => ''],

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'A', 'erp_product' => 'Over Arm Separator Disc',    'erp_nomenclature' => 'OASD', 'product_modified_name' => 'Over Arm Separator Disc'],

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'A', 'erp_product' => 'Lock Nuts',    'erp_nomenclature' => 'LN', 'product_modified_name' => ''],

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'A', 'erp_product' => 'C.O.C Cutter',    'erp_nomenclature' => 'COC', 'product_modified_name' => ''],

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'A', 'erp_product' => 'Fins',    'erp_nomenclature' => 'FIN', 'product_modified_name' => ''],

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'A', 'erp_product' => 'Plastic Shims', 'erp_nomenclature' => 'PS', 'product_modified_name' => ''],

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'A', 'erp_product' => 'Polyamide / Plastic Spacers', 'erp_nomenclature' => 'PLSP', 'product_modified_name' => ''],

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'A', 'erp_product' => 'Others', 'erp_nomenclature' => 'OTH', 'product_modified_name' => ''],

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'A', 'erp_product' => 'Tool Set-up Software', 'erp_nomenclature' => 'TSW', 'product_modified_name' => ''],

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'A', 'erp_product' => 'Book', 'erp_nomenclature' => 'Book', 'product_modified_name' => ''],

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'E', 'erp_product' => 'Work Rolls', 'erp_nomenclature' => 'WR', 'product_modified_name' => ''],

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'E', 'erp_product' => 'Intermediate Rolls', 'erp_nomenclature' => 'IMR', 'product_modified_name' => '' ],

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'E', 'erp_product' =>    'Drive Rolls', 'erp_nomenclature' => 'DR', 'product_modified_name' => ''],

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'E', 'erp_product' =>    'Idler Rolls', 'erp_nomenclature' => 'IDR', 'product_modified_name' => ''],

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'E', 'erp_product' =>    'Back-up Rolls', 'erp_nomenclature' => 'BUR', 'product_modified_name' => ''],

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'E', 'erp_product' =>    'SHAFTS', 'erp_nomenclature' => 'SHF', 'product_modified_name' => ''],

                ['unit_number' => 1, 'unit' => 'Tooling', 'group' => 'E', 'erp_product' =>    'Other', 'erp_nomenclature' => 'OTH', 'product_modified_name' => ''],

                // ============= RMR =============

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'F', 'erp_product' => 'Work Rolls', 'erp_nomenclature' => 'WR', 'product_modified_name' => '20 Hi Work Roll'],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'F', 'erp_product' => 'Drive Rolls', 'erp_nomenclature' => 'DR', 'product_modified_name' => '20 HI Drive Roll'],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'F', 'erp_product' => 'Intermediate rolls', 'erp_nomenclature' => 'IMR', 'product_modified_name' => '20 Hi IMR'],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'F', 'erp_product' => 'Idler Rolls', 'erp_nomenclature' => 'IDR', 'product_modified_name' => '20 Hi Idler Roll'],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'F', 'erp_product' => 'Other Rolls ', 'erp_nomenclature' => 'OTH', 'product_modified_name' => ''],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'F', 'erp_product' => 'Wiper rolls', 'erp_nomenclature' => 'wpr', 'product_modified_name' => '20 Hi Wiper Roll'],
 
                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'F', 'erp_product' => 'Side Support Rolls', 'erp_nomenclature' => 'SSR', 'product_modified_name' => '20 Hi Side support role'],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'G', 'erp_product' => 'Work Rolls',    'erp_nomenclature' => 'WR', 'product_modified_name' => '4 Hi - 6 Hi work roll'],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'G', 'erp_product' => 'Intermediate Rolls',    'erp_nomenclature' => 'IMR', 'product_modified_name' => '4 Hi - 6 Hi IMR roll'],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'G', 'erp_product' => 'Back-Up Rolls',    'erp_nomenclature' => 'BUR', 'product_modified_name' => '4 Hi - 6 Hi Backup roll'],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'G', 'erp_product' => 'Flattener Rolls',    'erp_nomenclature' => 'FLN', 'product_modified_name' => '4 Hi - 6 Hi Flatnner Roll'],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'G', 'erp_product' => 'Other Rolls',    'erp_nomenclature' => 'OTH', 'product_modified_name' => ''],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'G', 'erp_product' => 'Drive Rolls',    'erp_nomenclature' => 'DR', 'product_modified_name' => '4 Hi - 6Hi Drive Roll'],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'G', 'erp_product' => 'Pinch Roll',    'erp_nomenclature' => 'PR', 'product_modified_name' => '4 hi - 6 Hi Pinch Roll'],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'H', 'erp_product' => 'Leveler Rolls',    'erp_nomenclature' => 'LEV', 'product_modified_name' => 'Leveller Roll'],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'I', 'erp_product' => 'Shafts',    'erp_nomenclature' => 'SHF', 'product_modified_name' => 'Shafts'],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'I', 'erp_product' => 'Arbors',    'erp_nomenclature' => 'ARB', 'product_modified_name' => 'Arbors(SHAFT)'],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'I', 'erp_product' => 'Straightening Rolls',    'erp_nomenclature' => 'STR', 'product_modified_name' => 'Stratening Roll' ],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'J', 'erp_product' => 'Work Rolls',    'erp_nomenclature' => 'WR', 'product_modified_name' => ''],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'J', 'erp_product' => 'Skin Pass Rolls',    'erp_nomenclature' => 'SKP', 'product_modified_name' => ''],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'J', 'erp_product' => 'Intermediate rolls',    'erp_nomenclature' => 'IMR', 'product_modified_name' => ''],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'J', 'erp_product' => 'Back-Up rolls',    'erp_nomenclature' => 'BUR', 'product_modified_name' => ''],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'J', 'erp_product' =>    'Other rolls',    'erp_nomenclature' => 'OTH', 'product_modified_name' => ''],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'K', 'erp_product' =>    'Shear Blades',    'erp_nomenclature' => 'SB', 'product_modified_name' => ''],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'K', 'erp_product' => 'Others', 'erp_nomenclature' => 'OTH', 'product_modified_name' => ''],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'L', 'erp_product' => 'Tube Mill Rolls', 'erp_nomenclature' => 'TMR', 'product_modified_name' => ''],

                ['unit_number' => 2, 'unit' => 'RMR', 'group' => 'L', 'erp_product' => 'Others', 'erp_nomenclature' => 'OTH', 'product_modified_name' => ''],

                ['unit_number' => 3, 'unit' => 'TMR', 'group' => 'B', 'erp_product' => 'Tube Forming Rolls', 'erp_nomenclature' => 'TMR', 'product_modified_name' => ''],

                ['unit_number' => 3, 'unit' => 'TMR', 'group' => 'B', 'erp_product' => 'Cu-Al Rod mill rolls', 'erp_nomenclature' => 'CUR', 'product_modified_name' => ''],

                ['unit_number' => 3, 'unit' => 'TMR', 'group' => 'B', 'erp_product' => 'Straightening Rolls', 'erp_nomenclature' => 'STR',  'product_modified_name' => ''],

                ['unit_number' => 3, 'unit' => 'TMR', 'group' => 'B', 'erp_product' => 'Shaft', 'erp_nomenclature' => 'SHF',  'product_modified_name' => ''],

                ['unit_number' => 3,    'unit' => 'TMR', 'group' => 'B', 'erp_product' => 'Bar / Section Mill Rolls', 'erp_nomenclature' => 'BMR',  'product_modified_name' => ''],

                ['unit_number' => 3,    'unit' => 'TMR', 'group' => 'B', 'erp_product' => 'Roll Set Spacers', 'erp_nomenclature' => 'TSP',  'product_modified_name' => ''],

                ['unit_number' => 3,    'unit' => 'TMR', 'group' => 'B', 'erp_product' => 'Templates', 'erp_nomenclature' => 'TMP',  'product_modified_name' => ''],

                ['unit_number' => 3,    'unit' => 'TMR', 'group' => 'B', 'erp_product' => 'Fins', 'erp_nomenclature' => 'FIN',  'product_modified_name' => ''],

                ['unit_number' => 3,    'unit' => 'TMR', 'group' => 'B', 'erp_product' => 'Others', 'erp_nomenclature' => 'OTH',  'product_modified_name' => ''],

                ['unit_number' => 3,    'unit' => 'TMR', 'group' => 'C-SB', 'erp_product' => 'Shear Blades', 'erp_nomenclature' => 'SB',  'product_modified_name' => ''],

                ['unit_number' => 3,    'unit' => 'TMR', 'group' => 'C-SB', 'erp_product' => 'Others', 'erp_nomenclature' => 'OTH',  'product_modified_name' => ''],

                ['unit_number' => 3,    'unit' => 'TMR', 'group' => 'D', 'erp_product' => 'Guide Rolls', 'erp_nomenclature' => 'GDR',  'product_modified_name' => ''],

                ['unit_number' => 3,    'unit' => 'TMR', 'group' => 'D', 'erp_product' => 'Others', 'erp_nomenclature' => 'OTH',  'product_modified_name' => ''],

                ['unit_number' => 3,    'unit' => 'TMR', 'group' => 'C-TCOK', 'erp_product' => 'Tube Cut Off Knives', 'erp_nomenclature' => 'TCOK',  'product_modified_name' => ''],

                ['unit_number' => 3,    'unit' => 'TMR', 'group' => 'C-TCOK', 'erp_product' => 'Others', 'erp_nomenclature' => 'OTH',  'product_modified_name' => ''],

                ['unit_number' => 3,    'unit' => 'TMR', 'group' => 'M', 'erp_product' => 'Tube Forming Roll', 'erp_nomenclature' => 'TMR',  'product_modified_name' => ''],

                ['unit_number' => 3,    'unit' => 'TMR', 'group' => 'M', 'erp_product' => 'Other', 'erp_nomenclature' => 'OTH',  'product_modified_name' => ''],

            ]
        );

        $products = DB::table('product_masters')->get();

        // Insert each machine, generate its QR code, and update the machine_qr_code field
        foreach ($products as $product) {
            // == Insert the machine record and get its ID
            $jsonData   =  [ 'id' => $product->id, 'product' => $product->erp_product ];

            $jsonString =  json_encode($jsonData);

            // == Generate the QR code 
            $result = Builder::create()
                ->data($jsonString)
                ->size(300) // Set size in pixels
                ->margin(10) // Set margin in pixels
                ->build();

            $name = $product->unit.'-'. $product->erp_nomenclature .'-' .$product->group.'-' . time() . '.png';

            // Path where you want to save the QR code image
            $path = 'product-qrcodes/' . $name; // unique filename

            // Save the QR code image to storage (public disk)
            Storage::disk('public')->put($path, $result->getString());

            // Update the machine record with the QR code path
            DB::table('product_masters')
                ->where('id', $product->id)
                ->update(['product_qr_code' => $name]);
        }

    }
}
