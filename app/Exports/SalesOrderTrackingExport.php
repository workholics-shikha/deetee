<?php

namespace App\Exports;

use App\Models\SalesOrderTracking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesOrderTrackingExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // so_id, so_pid_primary, so_product_id,	sub_product_id,	machine_id,	operator_id, operation_id,	start_date_time,	end_date_time,	time_taken	,total_quantity, quantity_processed, reason,	roll_status,
        return SalesOrderTracking::select(
                'so.so_no as so_number',
                'u.name as operator_name',
                'pm.product_modified_name as so_product',
                'sp.sub_product_name as sub_product',
                'mm.machine',
                'om.operation_name as operation',
                'sot.start_date_time',
                'sot.end_date_time',
                'sot.time_taken',
                'sot.quantity_processed as roll',
                'sot.roll_status'
            )
            ->from('sales_order_trackings as sot')
            ->leftJoin('erp_sales_orders as so', 'sot.so_id', '=', 'so.so_id')
            ->leftJoin('users as u', 'sot.operator_id', '=', 'u.id') // adjust table if needed
            ->leftJoin('product_masters as pm', 'sot.so_product_id', '=', 'pm.id') // adjust table if needed
            ->leftJoin('sub_product as sp', 'sot.sub_product_id', '=', 'sp.id') // adjust table if needed
            ->leftJoin('operation_masters as om', 'sot.operation_id', '=', 'om.id') // adjust table if needed
            ->leftJoin('machine_master as mm', 'sot.machine_id', '=', 'mm.id') // adjust table if needed
            ->get();
    }

    public function headings(): array
    {
        return [
            'SO Number', 
            'Operator Name',
            'SO Product',
            'Sub Product',
            'Machine',
            'Operation',
            'Start Date time',
            'End Date time',
            'Total time',
            'Roll',
            'Roll status',
        ];
    }
}
