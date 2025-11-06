<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\{ErpItems, ErpMaterialList, ErpSalesOrder, ErpSoType, ErpSpecialOperation, SalesOrderProduct};
use Endroid\QrCode\Builder\Builder;
use Illuminate\Support\Facades\{Http, Log, Storage};

class ErpApiController extends Controller
{

    function callErpApi($url)
    {
        return $response = Http::withBasicAuth(ERP_USERNAME, ERP_PASSWORD)
            ->withHeaders([
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ])->get($url);

        echo "HTTP Status Code: " . $response->status() . "\n";
        echo "Response: " . $response;
    }

    // F1
    public function so_type()
    {
        $response = $this->callErpApi(ERP_LINK . '/OH_SoType');

        $data     = $response->json();

        foreach ($data as $datum) {

            ErpSoType::updateOrInsert(

                ['sotype_id'             => $datum['sotype_id']],
                [
                    'sotype_id'          => $datum['sotype_id'],
                    'sotype_name'        => $datum['sotype_name'],
                    'sotype_status'      => $datum['sotype_status'],
                    'sotype_deleted'     => $datum['sotype_deleted'],
                    'sotype_deletedby'   => $datum['sotype_deletedby'],
                    'sotype_deletedtime' => $datum['sotype_deletedtime']
                ]
            );
        }

        return (['status' => $response->status(), 'data' => $data]);
    }

    // F2
    public function so_type_by_id($id)
    {
        $response = $this->callErpApi(ERP_LINK . '/OH_SoTypeWithId/' . $id);
        return (['status' => $response->status(), 'data' => $response->json()]);
    }

    // F3
    public function material()
    {
        $response = $this->callErpApi(ERP_LINK . '/OH_material');

        $data     = $response->json();
 
        return (['status' => $response->status(), 'data' => $data]);
    }
 
 
    // F6
    public function special_operation_by_id($id)
    {
        $response = $this->callErpApi(ERP_LINK . '/OH_specialOperationWtihId/' . $id);
        return (['status' => $response->status(), 'data' => $response->json()]);
    }

    // F10 ======== not working ==========
    public function cpo_items_by_id($id)
    {
        $response = $this->callErpApi(ERP_LINK . '/cpoItemWithId/' . $id);
        return (['status' => $response->status(), 'data' => $response->json()]);
    }
   
    // F14
    public function show_scrunity_with_so_id($id)
    {
        $response = $this->callErpApi(ERP_LINK . '/OH_showSOScrutineyWithsoid/' . $id);
        return (['status' => $response->status(), 'data' => $response->json()]);
    }

    // F15
    public function show_scrunity_with_cpo_item_id($id)
    {
        $response = $this->callErpApi(ERP_LINK . '/OH_showSOScrutineyWithCpoitemid/' . $id);
        return (['status' => $response->status(), 'data' => $response->json()]);
    }

    // F16
    public function show_item_pass($id)
    {
        $response = $this->callErpApi(ERP_LINK . '/OH_showCPOItemPass/' . $id);
        return (['status' => $response->status(), 'data' => $response->json()]);
    }
 
    public function so_list()
    {
        $response = $this->callErpApi(ERP_LINK . '/OH_showSO');

        $allData     = $response->json();
        if (!empty($allData)) {
            foreach ($allData as $datum) {

                if ($datum['so_status'] != 'Closed') {

                    $data = [
                       "so_id"           => $datum['so_id'],
                        "so_customername" => $datum['so_customername'], 
                        "so_no"           => $datum['so_no'],
                        "so_status"       => $datum['so_status'],
                        "scr_status"       => $datum['scr_status'],
                        "so_unitid" => $datum['so_unitid'],
                        "so_unitname" => $datum['so_unitname'],
                        "so_group" => $datum['so_group'],
                        "so_groupid" => $datum['so_groupid'],
                        "soquantity" => !empty($datum['soquantity']) ? (int)$datum['soquantity'] : 0,
                        "so_date" => $datum['so_date'],
                        // "so_deliverytimeline" => $datum['so_deliverytimeline'],
                    ];

                    $order = ErpSalesOrder::firstOrNew(['so_id' => $datum['so_id']]);
                    $order->fill($data); // now includes so_id as well

                    if (!$order->exists) {
                        // New record
                        $order->created_at = now();
                        $order->updated_at = now();
                        $order->save();
                    } elseif ($order->isDirty()) {
                        // Existing, but changed
                        $order->updated_at = now(); // Optional if timestamps enabled
                        $order->save();
                    }
                }
            }
            Log::info('Cron is running at ' . now());

            // update QR Codes
            $erpSalesOrder = ErpSalesOrder::get();
            foreach ($erpSalesOrder as $salesOrder) {

                $jsonData   = ['id' => $salesOrder->id, 'so_no' => $salesOrder->so_no];
                $jsonString = json_encode($jsonData);

                // Generate the QR code
                $result = Builder::create()
                    ->data($salesOrder->id)
                    ->size(300) // Set size in pixels
                    ->margin(10) // Set margin in pixels
                    ->build();

                $name = $salesOrder->so_no . '-' . time() . '.png';

                $path = 'so-qrcodes/' . $name; // unique filename

                Storage::disk('public')->put($path, $result->getString());
                ErpSalesOrder::where('id', $salesOrder->id)->update(['so_qr_code' => $name]);
            }
        } 
        
        return redirect('/admin/sales-order');
    }
}
