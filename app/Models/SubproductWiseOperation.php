<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubproductWiseOperation extends Model
{
    use HasFactory;

    protected $table  = 'subproduct_wise_operation';

    
    protected $fillable = ['product_master_id', 'subproduct_id', 'operation_id', 'sub_operations'];

    public function product() {
        return $this->belongsTo(ProductMasters::class, 'product_master_id', 'id');
    }
     
    function sub_product() {
        return $this->belongsTo(SubProduct::class, 'subproduct_id', 'id') ;
    }

    function operations() {
        return $this->hasMany(OperationMaster::class, 'id', 'operation_id') ;
    }
  
    function operationData() {
        return $this->hasOne(OperationMaster::class, 'id', 'operation_id') ;
    }
  
}
