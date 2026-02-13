<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubProductMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sub_product')->insert(
            [

                // ==== Tooling
                ['product_master_id' => 1, 'sub_product_name' => 'Slitting Cutter with Precision Grade'],
                ['product_master_id' => 1, 'sub_product_name' => 'Slitting Cutter with Extra-Precision Grade'],

                ['product_master_id' => 2, 'sub_product_name' => 'Spacers with Precision Grade'],
                ['product_master_id' => 2, 'sub_product_name' => 'Spacers with Extra-Precision Grade'],
                ['product_master_id' => 2, 'sub_product_name' => 'Spacers Precision Grade by Decostyle'],
                ['product_master_id' => 2, 'sub_product_name' => 'Spacers Extra-Precision Grade by Decostyle'],

                ['product_master_id' => 3, 'sub_product_name' => 'Rubber Bonded Spacers with Precision Grade'],
                ['product_master_id' => 3, 'sub_product_name' => 'Rubber Bonded Spacers with Extra-Precision Grade'],

                ['product_master_id' => 4, 'sub_product_name' => 'Rubber Rings'],

                ['product_master_id' => 5, 'sub_product_name' => 'Light Weight Spacer with Precision Grade'],
                ['product_master_id' => 5, 'sub_product_name' => 'Light Weight Spacer with Extra-Precision Grade'],

                ['product_master_id' => 6, 'sub_product_name' => 'Over Arm Separator Disc'],
                ['product_master_id' => 6, 'sub_product_name' => 'Over Arm Separator Disc by Decostyle'],

                ['product_master_id' => 7, 'sub_product_name' => 'Not Available'],

                ['product_master_id' => 8, 'sub_product_name' => 'C.O.C Cutter'],

                ['product_master_id' => 9, 'sub_product_name' => 'Fins'],

                ['product_master_id' => 10, 'sub_product_name' => 'Outsourced'],

                ['product_master_id' => 11, 'sub_product_name' => 'Outsourced'],

                ['product_master_id' => 12, 'sub_product_name' => '(All Sub-Products of A Group)'],

                ['product_master_id' => 13, 'sub_product_name' => 'Outsourced'],

                ['product_master_id' => 14, 'sub_product_name' => 'Outsourced'],

                ['product_master_id' => 15, 'sub_product_name' => 'Work Rolls'],

                ['product_master_id' => 16, 'sub_product_name' => 'Intermediate Rolls'],

                ['product_master_id' => 17, 'sub_product_name' => 'Drive Rolls'],

                ['product_master_id' => 18, 'sub_product_name' => 'Idler Rolls'],

                ['product_master_id' => 19, 'sub_product_name' => 'Back-up Rolls'],

                ['product_master_id' => 20, 'sub_product_name' => 'SHAFTS'],

                ['product_master_id' => 21, 'sub_product_name' => 'Other'],

                // =====

                ['product_master_id' => 22, 'sub_product_name' => '20 Hi Work Roll Without Groove'],
                ['product_master_id' => 22, 'sub_product_name' => '20 Hi Work Roll with Single side Groove'],
                ['product_master_id' => 22, 'sub_product_name' => '20 Hi Work Roll with both side Groove'],

                ['product_master_id' => 23, 'sub_product_name' => '20 Hi Drive Roll'],
                ['product_master_id' => 23, 'sub_product_name' => '20 Hi DIN Spline Drive Roll'],

                ['product_master_id' => 24, 'sub_product_name' => '20 Hi IMR Without Groove'],
                ['product_master_id' => 24, 'sub_product_name' => '20 Hi IMR With Single Side Groove'],
                ['product_master_id' => 24, 'sub_product_name' => '20 Hi IMR with Both side Groove'],
                ['product_master_id' => 24, 'sub_product_name' => '20 Hi IMR with Single side Coupling'],
                ['product_master_id' => 24, 'sub_product_name' => '20 Hi IMR with Both side Coupling'],

                ['product_master_id' => 25, 'sub_product_name' => '20 Hi Idler Roll'],

                ['product_master_id' => 26, 'sub_product_name' => '(All Sub-Products of F Group)'],
                ['product_master_id' => 27, 'sub_product_name' => '20 Hi Wiper Roll With both side Journal'],

                ['product_master_id' => 28, 'sub_product_name' => '20 Hi Side Support Roll With both side Journal'],

                ['product_master_id' => 29, 'sub_product_name' => '4 Hi - 6 Hi Work Roll'],

                ['product_master_id' => 30, 'sub_product_name' => '4 Hi - 6 Hi IMR Roll'],

                ['product_master_id' => 31, 'sub_product_name' => '4 Hi - 6 Hi Backup Roll'],

                ['product_master_id' => 32, 'sub_product_name' => 'Flatner with both side Journal'],

                ['product_master_id' => 33, 'sub_product_name' => '(All Sub-Products of G Group)'],

                ['product_master_id' => 34, 'sub_product_name' => '4 Hi - 6 Hi Drive Roll Without Spline'],
                ['product_master_id' => 34, 'sub_product_name' => '4Hi - 6Hi Drive Roll with Spline'],

                ['product_master_id' => 35, 'sub_product_name' => 'Pinch Roll with Both side Journal'],

                ['product_master_id' => 36, 'sub_product_name' => 'Leveler Rolls With Both Side Journal'],
                ['product_master_id' => 36, 'sub_product_name' => 'Leveler Rolls With Both Side Bore'],

                ['product_master_id' => 37, 'sub_product_name' => 'Only Shaft'],

                ['product_master_id' => 39, 'sub_product_name' => 'Straightening Rolls without Shaft'],
                ['product_master_id' => 39, 'sub_product_name' => 'Straightening Rolls with Shaft'],

                ['product_master_id' => 40, 'sub_product_name' => 'Work Roll'],
                ['product_master_id' => 41, 'sub_product_name' => 'Skin Pass Roll'],
                ['product_master_id' => 42, 'sub_product_name' => 'IMR Roll'],
                ['product_master_id' => 43, 'sub_product_name' => 'Backup Roll'],
                ['product_master_id' => 44, 'sub_product_name' => '(All Sub-Products of J Group)'],

                ['product_master_id' => 45, 'sub_product_name' => 'Shear Blade'],

                ['product_master_id' => 46, 'sub_product_name' => '(All Sub-Products of K Group)'],

                ['product_master_id' => 49, 'sub_product_name' => 'Tube Forming Rolls'],

                ['product_master_id' => 50, 'sub_product_name' => 'Cu-Al Rod mill rolls'],
                ['product_master_id' => 51, 'sub_product_name' => 'Straightening Rolls'],
                ['product_master_id' => 53, 'sub_product_name' => 'Bar / Section Mill Rolls'],
                ['product_master_id' => 56, 'sub_product_name' => 'Fins'],
                ['product_master_id' => 60, 'sub_product_name' => 'Guide Rolls'],

            ],
        );
    }
}
