<?php

namespace App\Helpers;

use App\Models\{ErpSalesOrder, IdealCycleTime, MachineMaster, PassSheet, SalesOrderProduct, SOProductOperationDetails, SubproductWiseOperation, User};
use Illuminate\Support\Facades\DB;

class MyHelper
{

    public static function getCycleTime1($operationid, $parameter1, $parameter2, $getVal, $getVal2)
    {
        if ($parameter1 === "Material") {
            $materials = DB::table('material_with_cycle_time')
                ->select("$getVal as cycleTime", "diameter")
                ->where('operation', $operationid)
                ->where($getVal, '!=', '')
                ->get();

            $material = $materials->first(function ($item) use ($getVal2) {
                if (strpos($item->diameter, '-') !== false) {
                    [$min, $max] = explode('-', $item->diameter);
                    return $getVal2 >= (int) $min && $getVal2 <= (int) $max;
                }
                return false;
            });

            if ($material) {
                echo $material->cycleTime;
            }
        }

        if ($parameter1 === "Thickness") {
            $thicknessRecords = DB::table('thickness_with_cycle_time')
                ->select("cycle_time", "thickness")
                ->where('operation', $operationid)
                ->get();

            $thicknessRecord = $thicknessRecords->first(function ($item) use ($getVal) {
                if (strpos($item->thickness, '-') !== false) {
                    [$min, $max] = explode('-', $item->thickness);
                    return $getVal >= (int) $min && $getVal <= (int) $max;
                }
                return false;
            });

            if ($thicknessRecord) {
                echo $thicknessRecord->cycle_time;
            }
        }

        if ($parameter1 === "Bore") {
            $boreInput = $getVal ? $getVal : 0;
            $thicknessInput = $getVal2 ? $getVal2 : 0;
            $boreRecords = DB::table('bore_with_cycle_time')
                ->where('operation', $operationid)
                ->get();

            $matchedRow = $boreRecords->first(function ($row) use ($thicknessInput) {
                if (strpos($row->thickness, '-') !== false) {
                    [$min, $max] = explode('-', $row->thickness);
                    return $thicknessInput >= (int)$min && $thicknessInput <= (int)$max;
                }
                return false;
            });

            $columnToUse = null;
            $boreColumns = [
                '0_35'    => [0, 35],
                '36_50'   => [36, 50],
                '51_75'   => [51, 75],
                '76_100'  => [76, 100],
                '101_125' => [101, 125],
                '126_150' => [126, 150],
                '0_50' => [0, 50],
                '0_100' => [0, 100],
                '51_100' => [51, 100],
                '101_150' => [101, 150],
                '151_200' => [151, 200],
                '201_250' => [201, 250],
                '251_300' => [251, 300],
            ];

            foreach ($boreColumns as $column => [$min, $max]) {
                if ($boreInput >= $min && $boreInput <= $max) {
                    $columnToUse = $column;
                    break;
                }
            }

            $cycleTime = null;

            if ($matchedRow && $columnToUse && isset($matchedRow->$columnToUse)) {
                $cycleTime = $matchedRow->$columnToUse;
            }

            if ($cycleTime !== null) {
                echo  $cycleTime;
            } else {
            }
        }

        if ($parameter1 === "OD") {
        }
        if ($parameter1 === "Manual") {
            echo $cycleTime;
        }
        if ($parameter1 === "Corners") {
            $corner = $getVal ? $getVal : 0;
            $cornerRecord = DB::table('corners_with_cycle_time')
                ->select('cycle_time')
                ->where('operation', $operationid)
                ->where('corner', $corner)
                ->first();
            if ($cornerRecord) {
                echo $cornerRecord->cycle_time;
            }
        }
    }

    public static function getUsername($id)
    {
        return User::where('id', $id)->value('name');
    }

    public static function getMachinename($id)
    {
        return MachineMaster::where('id', $id)->value('machine');
    }

    public static function getMachineUnit($id)
    {
        $machine = MachineMaster::where('id', $id)
            ->select('unit_name', 'unit_number')
            ->first();

        return $machine ? [
            'unit_name' => $machine->unit_name,
            'unit_number' => $machine->unit_number
        ] : null;
    }

    public static function getCycleTimeForTooling($operationid, $so_id, $so_pid, $so_spid, $machine_id)
    {
        $getData = SubproductWiseOperation::where(['product_master_id' => $so_pid, 'subproduct_id' => $so_spid, 'operation_id' => $operationid])->first();

        $parameter1 = $getData->parameter1_label;
        $parameter2 = $getData->parameter2_label;

        $getSoId = ErpSalesOrder::find($so_id);
        $so_id = $getSoId->so_id;

        // Parameter 1 For TOOLING
        $salesOrderProduct = SalesOrderProduct::where(['so_id' => $so_id, 'product_id' => $so_pid, 'sub_product_id' => $so_spid])->first();

        $salesOrderProductOperatonDetails = SOProductOperationDetails::where(['so_id' => $so_id, 'product_id' => $so_pid, 'sub_product_id' => $so_spid, 'operation_id' => $operationid, 'sales_order_product_id' => $salesOrderProduct->id])->first();

        $getMapId = IdealCycleTime::where(['operation_id' => $operationid, 'machine_id' => $machine_id, 'sub_product_id' => $so_spid])->value('id');

        if ($getData->operation_type === "Fixed_ICT") {
            return $getData->fixed_ICT;
        }

        if ($getData->operation_type === "Manual_ICT" || $getData->operation_type === "Manual") {
            return $salesOrderProductOperatonDetails->cycle_time;
        }

        if ($parameter1 === "Material") {

            $getVal = $salesOrderProduct->material;
            $getVal2 = $salesOrderProduct->size1;

            // Split by '/' and take the first part (main material)
            $getInParts = explode('/', $getVal);
            $mainMaterial = strtoupper(trim($getInParts[0])); // Normalize case

            // Handle known materials (even if extra text is present)
            if (strpos($mainMaterial, 'D2') !== false) {
                $getVal = 'd2_h13';
            } elseif (strpos($mainMaterial, 'H13') !== false) {
                $getVal = 'd2_h13';
            } elseif (strpos($mainMaterial, 'D3') !== false) {
                $getVal = 'd3';
            } elseif (strpos($mainMaterial, 'EN31') !== false) {
                $getVal = 'en31';
            } else {
                return 'NA';
            }
            // Validate material type before query
            if (empty($getVal)) {
                return 'NA';
            }


            $materials  = DB::table('ict_material')
                ->select("$getVal as cycleTime", 'diameter_start_range', 'diameter_end_range')
                // ->where('operation_id', $operationid)
                // ->whereIn('sub_product_id', [$so_spid])
                //->where($getVal, '!=', '')
                // ->whereRaw("FIND_IN_SET(?, REPLACE(REPLACE(`machine_id`, '\"', ''), ' ', '')) > 0", [$machine_id])
                ->where('ict_id', $getMapId)
                ->get();

            // Check if collection is empty (corrected condition)
            if ($materials->isEmpty()) {
                return 'NA';
            }

            // Find matching material based on diameter range
            $material = $materials->first(function ($item) use ($getVal2) {
                // Ensure values are numeric before comparison
                $min = is_numeric($item->diameter_start_range) ? (float) $item->diameter_start_range : 0;
                $max = is_numeric($item->diameter_end_range) ? (float) $item->diameter_end_range : 0;
                $size = is_numeric($getVal2) ? (float) $getVal2 : 0;

                return $size >= $min && $size <= $max;
            });

            if ($material) {
                return $material->cycleTime;
            }

            // Return default value if no matching material found
            return 'NA';
        }

        if ($parameter1 === "Lw = (OD - ID)/2") {

            $getVal = $salesOrderProduct->size1;
            $getVal2 = $salesOrderProduct->size2;

            // ✅ Ensure numeric values (avoid non-numeric warning)
            if (!is_numeric($getVal) || !is_numeric($getVal2)) {
                return 'NA'; // or handle as needed (e.g., skip / throw error)
            }

            $gotVal = (($getVal - $getVal2) / 2);
            $cycleTime = 0;

            // ✅ Check if $gotVal is an integer (not a fraction)
            if (fmod($gotVal, 1) !== 0) {
                return 'NA'; // or handle fractional values as needed
            }

            if (in_array($operationid, [93, 94, 95, 99, 111])) {
                $thicknessRecords = DB::table('ict_thickness')
                    ->select("cycle_time", "thickness")
                    ->where('operation', $operationid)
                    ->get();

                $thicknessRecord = $thicknessRecords->first(function ($item) use ($gotVal) {
                    if (strpos($item->thickness, '-') !== false) {
                        [$min, $max] = explode('-', $item->thickness);
                        return $gotVal >= (int) $min && $gotVal <= (int) $max;
                    }
                    return false;
                });

                if ($thicknessRecord) {
                    return $thicknessRecord->cycle_time;
                }
            }
        }

        if ($parameter1 === "Ww = (OD-BORE)/2") {

            $getVal = $salesOrderProduct->size1;
            $getVal2 = $salesOrderProduct->size2;

            // ✅ Ensure numeric values (avoid non-numeric warning)
            if (!is_numeric($getVal) || !is_numeric($getVal2)) {
                return 'NA'; // or handle as needed (e.g., skip / throw error)
            }

            $gotVal = (($getVal - $getVal2) / 2);
            $cycleTime = 0;

            if (in_array($operationid, [81, 83])) {

                $thicknessRecords = DB::table('ict_thickness')
                    ->select("cycle_time", "thickness")
                    ->where('operation', $operationid)
                    ->get();

                $thicknessRecord = $thicknessRecords->first(function ($item) use ($gotVal) {
                    if (strpos($item->thickness, '-') !== false) {
                        [$min, $max] = explode('-', $item->thickness);
                        return $gotVal >= (int) $min && $gotVal <= (int) $max;
                    }
                    return false;
                });

                if ($thicknessRecord) {
                    return $thicknessRecord->cycle_time;
                }
            } else if (in_array($operationid, [85, 87])) {

                $thickness = $gotVal; // parameter1_value
                $bore = $salesOrderProduct->size3;

                $cycleTime = DB::table('ict_cnc_blanking')
                    ->where('thickness_min', '<=', $thickness)
                    ->where('thickness_max', '>=', $thickness)
                    ->where('vertical_min', '<=', $bore)
                    ->where('vertical_max', '>=', $bore)
                    ->where('operation', $operationid)
                    ->value('cycle_time'); // gets the single value

                if ($thickness > 0 && $thickness < 30) {
                    $cycleTime2 = '10';
                } else 	if ($thickness > 31 && $thickness < 60) {
                    $cycleTime2 = '15';
                } else 	if ($thickness > 61 && $thickness < 90) {
                    $cycleTime2 = '20';
                } else 	if ($thickness > 91 && $thickness < 130) {
                    $cycleTime2 = '25';
                } else 	if ($thickness > 131 && $thickness < 150) {
                    $cycleTime2 = '30';
                }
                return ($cycleTime + $cycleTime2);
            } else if (in_array($operationid, [86, 88])) {

                $param1 = $gotVal; // parameter1_value
                $od_param2 = $salesOrderProduct->size1;

                if ($param1 > 0 && $param1 < 30) {
                    $cycleTime = '10';
                } else 	if ($param1 > 31 && $param1 < 60) {
                    $cycleTime = '15';
                } else 	if ($param1 > 61 && $param1 < 90) {
                    $cycleTime = '20';
                } else 	if ($param1 > 91 && $param1 < 130) {
                    $cycleTime = '25';
                } else 	if ($param1 > 131 && $param1 < 150) {
                    $cycleTime = '30';
                }

                if ($od_param2 > 0 && $od_param2 < 30) {
                    $cycleTime2 = '10';
                } else 	if ($od_param2 > 31 && $od_param2 < 60) {
                    $cycleTime2 = '15';
                } else 	if ($od_param2 > 61 && $od_param2 < 90) {
                    $cycleTime2 = '20';
                } else 	if ($od_param2 > 91 && $od_param2 < 130) {
                    $cycleTime2 = '25';
                } else 	if ($od_param2 > 131 && $od_param2 < 150) {
                    $cycleTime2 = '30';
                }

                return ($cycleTime + $cycleTime2);
            }
        }

        if ($parameter1 === "OD" || $parameter2 === "Thickness") {

            $bore = $salesOrderProduct->size1;
            $thickness = $salesOrderProduct->size3;

            // ✅ Ensure numeric values (avoid non-numeric warning)
            if (!is_numeric($bore) || !is_numeric($thickness)) {
                return 'NA'; // or handle as needed (e.g., skip / throw error)
            }

            $cycleTime = DB::table('ict_cnc_blanking')
                ->where('thickness_min', '<=', $thickness)
                ->where('thickness_max', '>=', $thickness)
                ->where('vertical_min', '<=', $bore)
                ->where('vertical_max', '>=', $bore)
                ->where('operation', $operationid)
                ->value('cycle_time'); // gets the single value

            return $cycleTime;
        }

        if ($parameter1 === "Thickness" || $parameter1 === "OD") {

            $getVal = $salesOrderProduct->size3;

            // ✅ Ensure numeric values (avoid non-numeric warning)
            if (!is_numeric($getVal)) {
                return 'NA'; // or handle as needed (e.g., skip / throw error)
            }

            if ($operationid == 91) { // Round Keyway Milling

                $kw_size1 = $salesOrderProduct->kw_size1;

                $cycleTime = DB::table('ict_keyway_operations')
                    ->where('keyway_width_min', '<=', $kw_size1)
                    ->where('keyway_width_max', '>=', $kw_size1)
                    ->where('thickness_min', '<=', $getVal)
                    ->where('thickness_max', '>=', $getVal)
                    ->where('operation', $operationid)
                    ->value('cycle_time_minutes'); // gets the single value

                return $cycleTime;
            }

            $thicknessRecords = DB::table('ict_thickness')
                ->select("cycle_time", "thickness")
                ->where('operation', $operationid)
                ->get();

            $thicknessRecord = $thicknessRecords->first(function ($item) use ($getVal) {
                if (strpos($item->thickness, '-') !== false) {
                    [$min, $max] = explode('-', $item->thickness);
                    return $getVal >= (int) $min && $getVal <= (int) $max;
                }
                return false;
            });

            if ($thicknessRecord) {
                return $thicknessRecord->cycle_time;
            }
        }
    }

    public static function getCycleTimeForTMR($operationid, $so_id, $so_pid, $so_spid, $machine_id, $passId = Null)
    {

        $getData = SubproductWiseOperation::where(['product_master_id' => $so_pid, 'subproduct_id' => $so_spid, 'operation_id' => $operationid])->first();

        $parameter1 = $getData->parameter1_label;
        $parameter2 = $getData->parameter2_label;

        $getSoId = ErpSalesOrder::find($so_id);
        $so_id = $getSoId->so_id;
 
        // Parameter 1 For RMR
        $salesOrderProduct = SalesOrderProduct::where(['so_id' => $so_id, 'product_id' => $so_pid, 'sub_product_id' => $so_spid])->first();
  
        $salesOrderProductOperatonDetails = SOProductOperationDetails::where(['so_id' => $so_id, 'product_id' => $so_pid, 'sub_product_id' => $so_spid, 'operation_id' => $operationid, 'sales_order_product_id' => $salesOrderProduct->id])->first();
 
        $getMapId = IdealCycleTime::where(['operation_id' => $operationid, 'machine_id' => $machine_id])->value('id');
 
        if ($getData->operation_type === "Fixed_ICT") {
            return $getData->fixed_ICT;
        }

        if ($getData->operation_type === "Manual_ICT" || $getData->operation_type === "Manual") {
            if (!empty($salesOrderProductOperatonDetails->cycle_time)) {
                return $salesOrderProductOperatonDetails->cycle_time;
            } else {
                return 'NA';
            }
        }

        if ($getData->operation_type === "NA") {
            return 'NA';
        }

        $size1 = $salesOrderProduct->size1;
        $size2 = $salesOrderProduct->size2;
        $size3 = $salesOrderProduct->size3;
        $bs1_dia = $salesOrderProduct->bs1_blankdia;
        $bs1_depth = $salesOrderProduct->bs1_depthdia;

        if ($salesOrderProduct->measureunit == 'SET') {

            $passDetails = PassSheet::find($passId);
            $size1 = $passDetails->size1;
            $size2 = $passDetails->size2;
            $size3 = $passDetails->size3;
            $bs1_dia = $passDetails->bs1_dia;
            $bs1_depth = $passDetails->bs1_depth;
        }
        
        // Cutting
        if ($parameter1 === "Material" && $parameter2 === "Outer Diameter") {

            $getVal = $salesOrderProduct->material;
            $getVal2 = $size1;

            // return $getVal;

            // Split by '/' and take the first part (main material)
            $getInParts = explode('/', $getVal);
            $mainMaterial = strtoupper(trim($getInParts[0])); // Normalize case

            // Handle known materials (even if extra text is present)
            if (strpos($mainMaterial, 'D2') !== false) {
                $getVal = 'd2_h13';
            } elseif (strpos($mainMaterial, 'H13') !== false) {
                $getVal = 'd2_h13';
            } elseif (strpos($mainMaterial, 'D3') !== false) {
                $getVal = 'd3';
            } elseif (strpos($mainMaterial, 'EN31') !== false) {
                $getVal = 'en31';
            } else {
                return 'NA';
            }
            // Validate material type before query
            if (empty($getVal)) {
                return 'NA';
            }

            $materials  = DB::table('ict_material')
                ->select("$getVal as cycleTime", 'diameter_start_range', 'diameter_end_range')
                ->where('ict_id', $getMapId)
                ->get();

            // Check if collection is empty (corrected condition)
            if ($materials->isEmpty()) {
                return 'NA';
            }

            // Find matching material based on diameter range
            $material = $materials->first(function ($item) use ($getVal2) {
                // Ensure values are numeric before comparison
                $min = is_numeric($item->diameter_start_range) ? (float) $item->diameter_start_range : 0;
                $max = is_numeric($item->diameter_end_range) ? (float) $item->diameter_end_range : 0;
                $size = is_numeric($getVal2) ? (float) $getVal2 : 0;

                return $size >= $min && $size <= $max;
            });

            if ($material) {
                return $material->cycleTime;
            }

            // Return default value if no matching material found
            return 'NA';
        }

        // U Drilling
        if ($parameter1 === "Thickness") {

            $getVal = $size3;
            $thicknessRecords = DB::table('ict_thickness')
                ->select("cycle_time", "thickness")
                ->where('operation', $operationid)
                ->get();

            $thicknessRecord = $thicknessRecords->first(function ($item) use ($getVal) {
                if (strpos($item->thickness, '-') !== false) {
                    [$min, $max] = explode('-', $item->thickness);
                    return $getVal >= (int) $min && $getVal <= (int) $max;
                }
                return false;
            });

            if ($thicknessRecord) {
                return $thicknessRecord->cycle_time;
            }
        }

        // CNC Blanking - 1
        if ($parameter1 === "Bore (ID)" && $parameter2 === "Thickness") {

            $thickness = $size2; // parameter1_value
            $bore = $size3;

            $cycleTime = DB::table('ict_cnc_blanking')
                ->where('thickness_min', '<=', $thickness)
                ->where('thickness_max', '>=', $thickness)
                ->where('vertical_min', '<=', $bore)
                ->where('vertical_max', '>=', $bore)
                ->where('operation', $operationid)->where('machine_id', $machine_id)
                ->value('cycle_time'); // gets the single value

            if ($cycleTime) {
                return $cycleTime;
            }
        }

        // CNC Blanking - 2 & - 3
        if ($parameter1 === 'Ww = (OD-BORE)/2') {

            if (in_array($operationid, [48, 49])) {
                $getVal = $size1;
                $getVal2 = $size2;

                // ✅ Ensure numeric values (avoid non-numeric warning)
                if (!is_numeric($getVal) || !is_numeric($getVal2)) {
                    return 'NA'; // or handle as needed (e.g., skip / throw error)
                }

                $gotVal = (($getVal - $getVal2) / 2);
                $cycleTime = 0;

                $cycleTime = DB::table('ict_cnc_blanking')
                    ->where('vertical_min', '<=', $gotVal)
                    ->where('vertical_max', '>=', $gotVal)
                    ->where('operation', $operationid)->where('machine_id', $machine_id)
                    ->value('cycle_time'); // gets the single value

                return $cycleTime;
            }
        }

        // CNC Blanking - 4
        if ($parameter1 === 'Outer Diameter') {

            $bore = $size1; // parameter1_value
            $cycleTime = DB::table('ict_cnc_blanking')
                ->where('vertical_min', '<=', $bore)
                ->where('vertical_max', '>=', $bore)
                ->where('operation', $operationid)->where('machine_id', $machine_id)
                ->value('cycle_time'); // gets the single value

            if ($cycleTime) {
                return $cycleTime;
            }
        }

        if ($parameter1 === 'Length' && $parameter2 === 'Diameter') {
            $length = $salesOrderProductOperatonDetails->cycle_time; // parameter1_value
            $diameter = $salesOrderProductOperatonDetails->cycle_time_value2; // parameter1_value

            $cycleTime = DB::table('ict_cnc_blanking')
                ->where('thickness_min', '<=', $length)
                ->where('thickness_max', '>=', $length)
                ->where('vertical_min', '<=', $diameter)
                ->where('vertical_max', '>=', $diameter)
                ->where('operation', $operationid)
                ->value('cycle_time'); // gets the single value

            if ($cycleTime) {
                return $cycleTime;
            }
        }

        if ($parameter1 === 'Tap Size' && $parameter2 === 'Tapping Length') {
            $firstValue = $salesOrderProductOperatonDetails->cycle_time; // parameter1_value
            $getVal = $salesOrderProductOperatonDetails->cycle_time_value2; // parameter1_value

            // ✅ Ensure numeric values (avoid non-numeric warning)
            if (!is_numeric($getVal)) {
                return 'NA'; // or handle as needed (e.g., skip / throw error)
            }

            $cycleTime = DB::table('ict_tapping')->select('length', 'cycle_time')
                ->where('size', $firstValue)
                ->where('operation', $operationid)
                ->get(); // gets the single value

            $thicknessRecord = $cycleTime->first(function ($item) use ($getVal) {
                if (strpos($item->length, '-') !== false) {
                    [$min, $max] = explode('-', $item->length);
                    return $getVal >= (int) $min && $getVal <= (int) $max;
                }
                return false;
            });

            if ($thicknessRecord) {
                return $thicknessRecord->cycle_time;
            }
        }

        if ($parameter1 === 'Counter Size' && $parameter2 === 'Counter Depth') {

            $firstValue = $salesOrderProductOperatonDetails->cycle_time;       // parameter1_value
            $secondValue = $salesOrderProductOperatonDetails->cycle_time_value2; // parameter2_value

            // ✅ Ensure numeric values (avoid non-numeric warning)
            if (!is_numeric($firstValue) || !is_numeric($secondValue)) {
                return 'NA'; // or handle as needed
            }

            // ✅ Fetch tapping cycle time data for the given operation
            $cycleTimeRecords = DB::table('ict_tapping')
                ->select('size', 'length', 'cycle_time')
                ->where('operation', $operationid)
                ->get();

            // ✅ Find matching record based on Counter Size & Depth ranges
            $matchedRecord = $cycleTimeRecords->first(function ($item) use ($firstValue, $secondValue) {
                $sizeMatch = false;
                $lengthMatch = false;

                if (strpos($item->size, '-') !== false) {
                    [$minSize, $maxSize] = array_map('trim', explode('-', $item->size));
                    $sizeMatch = $firstValue >= (float) $minSize && $firstValue <= (float) $maxSize;
                }

                if (strpos($item->length, '-') !== false) {
                    [$minLen, $maxLen] = array_map('trim', explode('-', $item->length));
                    $lengthMatch = $secondValue >= (float) $minLen && $secondValue <= (float) $maxLen;
                }

                return $sizeMatch && $lengthMatch;
            });

            if ($matchedRecord) {
                return $matchedRecord->cycle_time;
            }

            return 'NA'; // No matching record found
        }

        if ($parameter1 === 'Thickness' && $parameter2 === 'Keyway') {

            $thickness = $size3;
            $keyway = $salesOrderProductOperatonDetails->kw_size1;

            if (!is_numeric($thickness) || !is_numeric($keyway)) {
                return 'NA'; // or handle as needed
            }

            $cycleTime = DB::table('ict_cnc_blanking')
                ->where('thickness_min', '<=', $thickness)
                ->where('thickness_max', '>=', $thickness)
                ->where('vertical_min', '<=', $keyway)
                ->where('vertical_max', '>=', $keyway)
                ->where('operation', $operationid)
                ->value('cycle_time');

            if ($cycleTime) {
                return $cycleTime;
            }
            return 'NA';
        }

        if ($parameter1 === "Lw = (OD - ID)/2") {

            $getVal = $size1;
            $getVal2 = $size2;

            // ✅ Ensure numeric values (avoid non-numeric warning)
            if (!is_numeric($getVal) || !is_numeric($getVal2)) {
                return 'NA'; // or handle as needed (e.g., skip / throw error)
            }

            $gotVal = (($getVal - $getVal2) / 2);
            $cycleTime = 0;

            if (in_array($operationid, [61, 62])) {
                $thicknessRecords = DB::table('ict_thickness')
                    ->select('cycle_time', 'thickness')
                    ->where('operation', $operationid)
                    ->where('machine_id', $machine_id)
                    ->get();

                $thicknessRecord = $thicknessRecords->first(function ($item) use ($gotVal) {
                    if (strpos($item->thickness, '-') !== false) {
                        [$min, $max] = explode('-', $item->thickness);
                        return $gotVal >= (int) $min && $gotVal <= (int) $max;
                    }
                    return false;
                });

                if ($thicknessRecord) {
                    return $thicknessRecord->cycle_time;
                }
            }
        }

        // Bore Turning With Under Cut - Single Side
        if ($parameter1 === "Thickness" && $parameter2 === "Bore") {

            $thickness = $size3; // parameter1_value
            $bore = $size2;
            if (in_array($operationid, [63, 64, 65, 66])) {
                $cycleTime = DB::table('ict_cnc_blanking')
                    ->where('thickness_min', '<=', $thickness)
                    ->where('thickness_max', '>=', $thickness)
                    ->where('vertical_min', '<=', $bore)
                    ->where('vertical_max', '>=', $bore)
                    ->where('operation', $operationid)->where('machine_id', $machine_id)
                    ->value('cycle_time'); // gets the single value

                if ($cycleTime) {
                    return $cycleTime;
                }
            }
        }

        if ($parameter1 === "Thickness" && $parameter2 === "(Kw + Kd +Kd) x T") {

            $kw_size1 = $salesOrderProduct->kw_size1;
            $kw_depth = $salesOrderProduct->kw_depth;
            $param2 = ($kw_size1 + $kw_depth + $kw_depth) * $size3; // (kw_size1 + kw_depth + kw_depth) x size3

            if (!is_numeric($param2)) {
                return 'NA'; // or handle as needed (e.g., skip / throw error)
            }

            $cycleTime = DB::table('ict_tapping')->select('length', 'cycle_time')
                ->where('operation', $operationid)
                ->get(); // gets the single value

            $thicknessRecord = $cycleTime->first(function ($item) use ($size3) {
                if (strpos($item->length, '-') !== false) {
                    [$min, $max] = explode('-', $item->length);
                    return $size3 >= (int) $min && $size3 <= (int) $max;
                }
                return false;
            });
            // return $kw_size1 .'---'. $kw_depth.'---'.$size3;
            return ($param2 / $thicknessRecord->cycle_time);
        }

        if ($parameter1 === "Bearing Seat Size" && $parameter2 === "Bearing Seat Depth") {

            $cycleTime = DB::table('ict_bearing_seat')
                ->select('seat_size', 'seat_depth', 'cycle_time')
                ->where('operation', $operationid)
                ->get();

            optional($cycleTime->first(function ($item) use ($bs1_dia, $bs1_depth) {
                // Check seat_size range
                if (strpos($item->seat_size, '-') === false || strpos($item->seat_depth, '-') === false) {
                    return false;
                }

                [$minSize, $maxSize] = array_map('trim', explode('-', $item->seat_size));
                [$minDepth, $maxDepth] = array_map('trim', explode('-', $item->seat_depth));

                return $bs1_dia >= (float)$minSize && $bs1_dia <= (float)$maxSize && $bs1_depth >= (float)$minDepth && $bs1_depth <= (float)$maxDepth;
            }))->cycle_time ?? null;
        }

        if ($parameter1 === "Corners") {  
             
            $corner = $salesOrderProductOperatonDetails->cycle_time;
            $cornerRecord = DB::table('ict_tapping')
                ->select('cycle_time')
                ->where('operation', $operationid)
                ->where('size', $corner)->where('machine_id', $machine_id)
                ->first();
 
            if ($cornerRecord) {
                return $cornerRecord->cycle_time;
            }
        }
    }
 
    public static function getCycleTimeForRMR($operationid, $so_id, $so_pid, $so_spid, $machine_id)
    {
        $getData = SubproductWiseOperation::where(['product_master_id' => $so_pid, 'subproduct_id' => $so_spid, 'operation_id' => $operationid])->first();

        $parameter1 = $getData->parameter1_label;
        $parameter2 = $getData->parameter2_label;

        $getSoId = ErpSalesOrder::find($so_id);
        $so_id = $getSoId->so_id;

        // Parameter 1 For RMR
        $salesOrderProduct = SalesOrderProduct::where(['so_id' => $so_id, 'product_id' => $so_pid, 'sub_product_id' => $so_spid])->first();

        $salesOrderProductOperatonDetails = SOProductOperationDetails::where(['so_id' => $so_id, 'product_id' => $so_pid, 'sub_product_id' => $so_spid, 'operation_id' => $operationid, 'sales_order_product_id' => $salesOrderProduct->id])->first();

        $getMapId = IdealCycleTime::where(['operation_id' => $operationid, 'machine_id' => $machine_id, 'sub_product_id' => $so_spid])->value('id');

        if ($getData->operation_type === "Fixed_ICT") {
            return $getData->fixed_ICT;
        }

        if ($getData->operation_type === "Manual_ICT" || $getData->operation_type === "Manual") {
            return $salesOrderProductOperatonDetails->cycle_time;
        }

        if ($getData->operation_type === "NA") {
            return 'NA';
        }

        if ($parameter1 === "Outer Diameter" && $parameter2 === "Total Length") {

            $getVal = $salesOrderProduct->size1;
            $getVal2 = $salesOrderProduct->size3;

            $cycleTime = DB::table('ict_rmr_2matrix')
                ->where('od_min', '<=', $getVal)
                ->where('od_max', '>=', $getVal)

                ->where('length_min', '<=', $getVal2)
                ->where('length_max', '>=', $getVal2)

                ->where('operation', $operationid)
                ->where('sub_product_id', $so_spid)
                ->where('table_parts', '1')
                ->value('total_cycle_time'); // gets the single value
 
            if ( (in_array($so_spid, [1,2,3,12,13]) && in_array($operationid, [30, 31])) 
                || (in_array($so_spid, [4,5,6]) && in_array($operationid, [30, 21])) 
                || (in_array($so_spid, [7,8,9,10,11]) && in_array($operationid, [30, 23])) 
                || (in_array($so_spid, [14,15,16,18,19,30,31,33]) && in_array($operationid, [31])) 
                || (in_array($so_spid, [17,20,32]) && in_array($operationid, [29])) 
                || (in_array($so_spid, [21]) && in_array($operationid, [21, 33])) 
                || (in_array($so_spid, [22]) && in_array($operationid, [24, 33])) 
                || (in_array($so_spid, [23]) && in_array($operationid, [21])) 
                || (in_array($so_spid, [24]) && in_array($operationid, [24])) 
               ) {

                /** 1,2,3,12,13  -  31,30
                 *  4,5,6,  -  30,21, 
                 *  7,8,9,10,11  -  30, 23,  
                 *  14,15,16,18,19,30, 31, 33  -  31
                 *  17,20,32 - 29
                 *  21 - 21, 33,
                 *  22 - 24, 33
                 *  23 - 21, 
                 *  24 - 24 
                 */ 

                $cycleTime2 = DB::table('ict_rmr_2matrix')
                    ->where('od_min', '<=', $getVal)
                    ->where('od_max', '>=', $getVal)

                    ->where('length_min', '<=', $getVal2)
                    ->where('length_max', '>=', $getVal2)

                    ->where('operation', $operationid)
                    ->where('sub_product_id', $so_spid)
                    ->where('table_parts', '2')
                    ->value('total_cycle_time');

                return ($cycleTime + $cycleTime2);
            }

            return $cycleTime;
        }

        if ($parameter1 === "Outer Diameter") {

            $getVal = $salesOrderProduct->size1;

            if (in_array($operationid, [41, 42, 43])) {
                return '98';
            }

            // Validate material type before query
            if (empty($getVal)) {
                return 'NA';
            }
        }
    }

}
