<?php

namespace Database\Seeders;

use Endroid\QrCode\Builder\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MachineMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('machine_master')->insert(
            [
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Microtech - CNC',
                    'machine_type' => 'CNC',
                    'section' => 'Production',
                    'sub_section' => 'CNC',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'TEXEL 3020 CNC',
                    'machine_type' => 'CNC',
                    'section' => 'Production',
                    'sub_section' => 'CNC',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'TEXEL 3025 CNC',
                    'machine_type' => 'CNC',
                    'section' => 'Production',
                    'sub_section' => 'CNC',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'TEXEL 7015 CNC',
                    'machine_type' => 'CNC',
                    'section' => 'Production',
                    'sub_section' => 'CNC',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'TEXEL 5530',
                    'machine_type' => 'CNC',
                    'section' => 'Production',
                    'sub_section' => 'CNC',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'ECONO CNC',
                    'machine_type' => 'CNC',
                    'section' => 'Production',
                    'sub_section' => 'CNC',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'DMTG Lathe',
                    'machine_type' => 'Lathe',
                    'section' => 'Production',
                    'sub_section' => 'CNC',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Milling VF3',
                    'machine_type' => 'Milling',
                    'section' => 'Production',
                    'sub_section' => 'CNC',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'JIG BORING',
                    'machine_type' => 'Milling',
                    'section' => 'Production',
                    'sub_section' => 'CNC',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Jupiter Lathe',
                    'machine_type' => 'Lathe',
                    'section' => 'Production',
                    'sub_section' => 'CNC',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Anurag Lathe',
                    'machine_type' => 'Lathe',
                    'section' => 'Production',
                    'sub_section' => 'CNC',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'FN2',
                    'machine_type' => 'Milling',
                    'section' => 'Production',
                    'sub_section' => 'Grinding',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Lathe 1',
                    'machine_type' => 'Lathe',
                    'section' => 'Production',
                    'sub_section' => 'Grinding',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Lathe 2',
                    'machine_type' => 'Lathe',
                    'section' => 'Production',
                    'sub_section' => 'Grinding',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Lathe 3',
                    'machine_type' => 'Lathe',
                    'section' => 'Production',
                    'sub_section' => 'Grinding',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => '4.2 Russian',
                    'machine_type' => 'Cylindrical Grinding',
                    'section' => 'Production',
                    'sub_section' => 'Grinding',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Toss',
                    'machine_type' => 'Cylindrical Grinding',
                    'section' => 'Production',
                    'sub_section' => 'Grinding',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'CG - First',
                    'machine_type' => 'Cylindrical Grinding',
                    'section' => 'Production',
                    'sub_section' => 'Grinding',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'CG - Second',
                    'machine_type' => 'Cylindrical Grinding',
                    'section' => 'Production',
                    'sub_section' => 'Grinding',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Nexus',
                    'machine_type' => 'Cylindrical Grinding',
                    'section' => 'Production',
                    'sub_section' => 'Grinding',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'G-22',
                    'machine_type' => 'Cylindrical Grinding',
                    'section' => 'Production',
                    'sub_section' => 'Grinding',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'New Churchill',
                    'machine_type' => 'Cylindrical Grinding',
                    'section' => 'Production',
                    'sub_section' => 'Grinding',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Premeto',
                    'machine_type' => 'Cylindrical Grinding',
                    'section' => 'Production',
                    'sub_section' => 'Grinding',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'South',
                    'machine_type' => 'Cylindrical Grinding',
                    'section' => 'Production',
                    'sub_section' => 'Grinding',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Old Churchill',
                    'machine_type' => 'Cylindrical Grinding',
                    'section' => 'Production',
                    'sub_section' => 'Grinding',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'HMC',
                    'machine_type' => 'CNC',
                    'section' => 'Production',
                    'sub_section' => 'CNC',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Cantaluppi',
                    'machine_type' => 'Surface Grinder',
                    'section' => 'Planning',
                    'sub_section' => 'K - Group',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Pinacle',
                    'machine_type' => 'Surface Grinder',
                    'section' => 'Planning',
                    'sub_section' => 'K - Group',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Rosa',
                    'machine_type' => 'Surface Grinder',
                    'section' => 'Planning',
                    'sub_section' => 'K - Group',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'VMC',
                    'machine_type' => 'VMC',
                    'section' => 'Planning',
                    'sub_section' => 'K - Group',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Plano',
                    'machine_type' => 'Milling',
                    'section' => 'Planning',
                    'sub_section' => 'K - Group',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Big Bandsaw',
                    'machine_type' => 'Bandsaw',
                    'section' => 'Planning',
                    'sub_section' => 'Bandsaw',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Small Bandsaw',
                    'machine_type' => 'Bandsaw',
                    'section' => 'Planning',
                    'sub_section' => 'Bandsaw',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Vertical Bandsaw',
                    'machine_type' => 'Bandsaw',
                    'section' => 'Planning',
                    'sub_section' => 'Bandsaw',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Big Drill',
                    'machine_type' => 'Drill',
                    'section' => 'Planning',
                    'sub_section' => 'PHT',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Small Drill',
                    'machine_type' => 'Drill',
                    'section' => 'Planning',
                    'sub_section' => 'PHT',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'DMTG 6180',
                    'machine_type' => 'CNC',
                    'section' => 'Planning',
                    'sub_section' => 'CNC',
                ],
                [
                    'unit_number' => 2,
                    'unit_name' => 'RMR',
                    'machine' => 'Stress Relieve',
                    'machine_type' => 'Stress Relieve',
                    'section' => 'Production',
                    'sub_section' => 'CNC',
                ],

            ]
        );

        $machines = DB::table('machine_master')->get();

        // Insert each machine, generate its QR code, and update the machine_qr_code field
        foreach ($machines as $machine) {
            // Insert the machine record and get its IDph
            $jsonData = ['id' => $machine->id, 'machine' => $machine->machine];

            $jsonString = json_encode($jsonData);

            // Generate the QR code
            $result = Builder::create()
                ->data($jsonString)
                ->size(300) // Set size in pixels
                ->margin(10) // Set margin in pixels
                ->build();

            $name = $machine->machine.'-'.time().'.png';

            // Path where you want to save the QR code image
            $path = 'machine-qrcodes/'.$name; // unique filename

            // Save the QR code image to storage (public disk)
            Storage::disk('public')->put($path, $result->getString());

            // Update the machine record with the QR code path
            DB::table('machine_master')
                ->where('id', $machine->id)
                ->update(['machine_qr_code' => $name]);
        }
    }
}
