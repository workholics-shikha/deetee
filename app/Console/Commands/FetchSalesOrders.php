<?php

namespace App\Console\Commands;

use App\Http\Controllers\API\ErpApiController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchSalesOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:sales-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $controller = new ErpApiController;
            $controller->so_list();
  
            Log::info('Sales orders fetched via controller.');
        } catch (\Exception $e) {
            Log::error('Error fetching sales orders: '.$e->getMessage());
        }
    }
}
