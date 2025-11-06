<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder; 
use Endroid\QrCode\Builder\Builder;
use Illuminate\Support\Facades\{Storage, DB};

class OperationMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('operation_masters')->insert([

            [ 'operation_name' => 'Raw Material'],

            [ 'operation_name' => 'Bandsaw cutting' ],

            [ 'operation_name' => 'Cutting' ],

            [ 'operation_name' => 'Finish Pc Job Work - Job Card Made' ],

            [ 'operation_name' => 'Rubber Vulcanization - RR' ],
            [ 'operation_name' => 'Rubber Vulcanization - BSR' ],
            [ 'operation_name' => 'Rubber Vulcanization - Job Work - BSR' ],
            [ 'operation_name' => 'Rubber OD Grinding' ],
 
            [ 'operation_name' => 'Blanking' ],
            [ 'operation_name' => 'Blanking-1' ],
            [ 'operation_name' => 'Blanking-2' ],
            [ 'operation_name' => 'Blanking-3' ],
            [ 'operation_name' => 'Blanking-4' ],
            [ 'operation_name' => 'Blanking-5' ],
            [ 'operation_name' => 'Blanking-6' ],
            [ 'operation_name' => 'Blanking-7' ],

            [ 'operation_name' => 'CNC Blanking' ],
            
            [ 'operation_name' => 'Blanking Job Work' ],
            
            [ 'operation_name' => 'Heat Treatment' ],
            [ 'operation_name' => 'Heat Treatment-1' ],
            [ 'operation_name' => 'Heat Treatment-2' ],

            [ 'operation_name' => 'CNC Profiling' ],

            [ 'operation_name' => 'Hard Turning' ],
            [ 'operation_name' => 'Hard Turning-1' ],
            [ 'operation_name' => 'Hard Turning-2' ],
            [ 'operation_name' => 'Hard Turning-3' ],
            [ 'operation_name' => 'Hard Turning-4' ],

            [ 'operation_name' => 'Hard Turning Profile - 1' ],
            [ 'operation_name' => 'Hard Turning Profile - 2' ],
            
            [ 'operation_name' => 'Stress Relieve' ],
            [ 'operation_name' => 'Fitter work' ],
            
            [ 'operation_name' => 'Grinding' ],
            [ 'operation_name' => 'Grinding-1' ],
            [ 'operation_name' => 'Grinding-2' ],
            [ 'operation_name' => 'Grinding-3' ],
            [ 'operation_name' => 'Grinding-4' ],
            [ 'operation_name' => 'Grinding-5' ],
            
            [ 'operation_name' => 'Thickness Grinding' ],
            [ 'operation_name' => 'Semi Final Thickness Grinding - 1' ],
            [ 'operation_name' => 'Semi Final Thickness Grinding - 2' ],
            [ 'operation_name' => 'Semi Final Thickness Grinding - 3' ],

            [ 'operation_name' => 'Metal OD Grinding' ],
            
            [ 'operation_name' => 'QAD' ],
            
            [ 'operation_name' => 'Lapping - 1' ],
            [ 'operation_name' => 'Lapping - 2' ],
            [ 'operation_name' => 'Lapping - 3' ],
            [ 'operation_name' => 'Lapping - 4' ],
             
            [ 'operation_name' => 'Drill+ Tap' ],
            [ 'operation_name' => 'Drill+ Tap-1' ],
            [ 'operation_name' => 'Drill+ Tap-2' ],
            
            [ 'operation_name' => 'Manual lathe' ],
            [ 'operation_name' => 'Manual lathe-1' ],
            [ 'operation_name' => 'Manual lathe-2' ],
            [ 'operation_name' => 'Manual lathe-3' ],
            [ 'operation_name' => 'Manual lathe-4' ],
            
            [ 'operation_name' => 'Milling' ],
            [ 'operation_name' => 'Milling-2' ],

            [ 'operation_name' => 'Milling Work' ],
            [ 'operation_name' => 'Round Keyway Milling' ],
            
            [ 'operation_name' => 'Keyway Slotting' ],
            [ 'operation_name' => 'Keyway Chamfering' ],
            
             
            [ 'operation_name' => 'PHT Inspection' ],

            [ 'operation_name' => 'Bore Turning' ],
            [ 'operation_name' => 'Bore Grinding' ],

            [ 'operation_name' => 'Other Work' ],

            [ 'operation_name' => 'Tool Cutter' ],

            [ 'operation_name' => 'CNC Turning - 1' ],
            [ 'operation_name' => 'CNC Turning - 2' ],
            [ 'operation_name' => 'CNC Turning - 3' ],
            [ 'operation_name' => 'CNC Turning - 4' ],
            [ 'operation_name' => 'CNC Turning - 5' ],
            [ 'operation_name' => 'CNC Turning - 6' ],

            [ 'operation_name' => 'Final Thickness Grinding on CNC' ],
            [ 'operation_name' => 'Final Thickness Grinding on CNC - 1' ],
            [ 'operation_name' => 'Final Thickness Grinding on CNC - 2' ],

            [ 'operation_name' => 'Buffing' ],

            [ 'operation_name' => 'Hard Turning Job Work - 1' ],
            [ 'operation_name' => 'Hard Turning Job Work - 2' ],

            [ 'operation_name' => 'Dirt Groove Machining' ],

            [ 'operation_name' => 'Bevel Grinding - 1' ],
            [ 'operation_name' => 'Bevel Grinding - 2' ],
            [ 'operation_name' => 'Bevel Grinding - 3' ],

            [ 'operation_name' => 'Special Operation - Threading (Int, Ext), Grooving (Radial, Int, Axial)' ],

            [ 'operation_name' => 'Precision Turning Work - Rib Thickness, Boss OD, Fin OD, Fin Angle, Bevel' ],

            [ 'operation_name' => 'Plating Job Work' ],
            
            [ 'operation_name' => 'Template Matching' ],

            [ 'operation_name' => 'Polishing Job Work' ],

            [ 'operation_name' => 'Junction Grinding' ],

            [ 'operation_name' => 'QAD' ], 
            
            [ 'operation_name' => 'Dirt Groove Cleaning' ], 

            [ 'operation_name' => 'Shear Blades Parting' ], 

            [ 'operation_name' => 'Berral+Journal-1st & 2nd Side' ], 

            [ 'operation_name' => 'STEP+GROOVE-1ST SIDE' ], 

            [ 'operation_name' => 'STEP+GROOVE-2ND SIDE' ], // 94

            [ 'operation_name' => 'Shink Fitting on journal diameter (Manual)' ], //95

            [ 'operation_name' => 'Flate+Keyway+Drill work.' ], //96

            [ 'operation_name' => 'Barrel Grinding.' ], //97

            [ 'operation_name' => 'All Journal & Barrel Turning + Threading.' ], //98

            [ 'operation_name' => 'Facing + centering + length turning.' ], //99

            [ 'operation_name' => 'Final rework- (Polish+Step Remove).' ], //100

            [ 'operation_name' => 'Profile Hardturning.' ], //101

            [ 'operation_name' => 'Final Bore Grinding.' ], //102

            [ 'operation_name' => 'Facing + Bore hard turning.' ], //103

            [ 'operation_name' => 'Roll Hardning & shaft turning.' ], //104

            [ 'operation_name' => 'Shaft grinding.' ], //105

            [ 'operation_name' => 'Final Shaft grinding.' ], //106

            [ 'operation_name' => 'Key way + Slot + face drilling+Tapping.' ], //107

            [ 'operation_name' => 'Shrink Fitting of roll & shaft.' ], //108

            [ 'operation_name' => 'Keyway+Drilling +Tapping.' ], //109

            [ 'operation_name' => 'Final rework-1st side.' ], //110

            [ 'operation_name' => 'Final rework-2nd side.' ], //111

            [ 'operation_name' => 'Berral+Jouranal-one Side.' ], //112

            [ 'operation_name' => 'BERRAL+JOURNAL-2ND SIDE' ], //113

            [ 'operation_name' => 'Final rework Step+Groove+Threading.' ], //114

            [ 'operation_name' => 'Final rework STEP+GROOVE-1ST SIDE.' ], //115

            [ 'operation_name' => 'Final rework STEP+GROOVE-2ND SIDE.' ], //116

            [ 'operation_name' => 'Soft milling work.' ], //117

            [ 'operation_name' => 'Centering + facing + Length + drilling + journal turning both side.' ], //118

            [ 'operation_name' => 'Barrel+Short side Journal hard turning+grove+thread 1st side.' ], //119

            [ 'operation_name' => 'Final Thickness Grinding.' ], //120

            [ 'operation_name' => 'Finish Pc Job Work - Job Card Received.' ], //121

            [ 'operation_name' => 'Berral+Journal' ], //122

            [ 'operation_name' => 'Driling+Tapping-1st Side' ], //123

            [ 'operation_name' => 'Drilling+Tapping -2nd Side' ], //124

            [ 'operation_name' => 'Spline cutting' ], //125

            [ 'operation_name' => 'Din Spline cutting (outside Vendor)' ], //126

            [ 'operation_name' => 'Drilling+Tapping -both Side' ], //127

            [ 'operation_name' => 'Flate+Keyway' ], //128

            [ 'operation_name' => 'Drilling+Tapping+Flate+Keyway.' ], //129

            [ 'operation_name' => 'Centering + facing+ drilling+ jounal turning 1st side.' ], //130

            [ 'operation_name' => 'Centering + facing+drilling+ jounal turning 2nd side.' ], //131

        ]);
 
       /* $operations = DB::table('operation_masters')->get();

        // Insert each machine, generate its QR code, and update the machine_qr_code field
        foreach ($operations as $operation) {
            // Insert the machine record and get its IDph
            $jsonData = ['id'=> $operation->id, 'type'=> 'operation', 'operation_name'=> $operation->operation_name ];

            $jsonString = json_encode($jsonData);

            // Generate the QR code
            $result = Builder::create()
                ->data($jsonString)
                ->size(300) // Set size in pixels
                ->margin(10) // Set margin in pixels
                ->build();

            $name = $operation->id.'-' . time() . '.png';

            // Path where you want to save the QR code image
            $path = 'operation-qr-codes/' . $name; // unique filename

            // Save the QR code image to storage (public disk)
            Storage::disk('public')->put($path, $result->getString());

            // Update the machine record with the QR code path
            DB::table('operation_masters')
                ->where('id', $operation->id)
                ->update(['operation_barcode' => $name]);

        } */

    }
}
									
 		
