<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteCardList extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'product_name', 'sub_product_id', 'sub_product_name', 'operation_stage', 'operation_id', 'operation_name', 'operation_barcode'];

    public function getOperationBarcodeAttribute($value)
    {
        if ($value) {
            return asset('storage/operation-qr-codes/'.$value);
        }
        return asset(DEFAULT_QR);
    }
    
}
