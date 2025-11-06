<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SOProductOperationDetails extends Model
{
    use HasFactory;

    protected $table    = 'sales_order_product_operation_details';

    protected $fillable = [ 'so_id', 'so_no', 'sales_order_product_id', 'product_id', 'sub_product_id', 'operation_id', 'operation_name', 'operation_stage', 'operation_qr_code', 'qty', 'processed_qty', 'process_status', 'final_status', 'operation_status'];
  
    public function getOperationQrCodeAttribute($value)
    {
        if ($value) {
              return asset('storage/operation-qr-codes/' . $value);
        }
        return asset(DEFAULT_QR);
    }

    public function operation_params()
    {
        return $this->belongsTo(OperationMaster::class, 'operation_id', 'id')
            ->select('id', 'parameter1', 'parameter2');
    }

}
