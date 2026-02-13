<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubProduct extends Model
{
    use HasFactory;

    protected $table = 'sub_product';

    protected $fillable = ['product_master_id', 'sub_product_name', 'set_of_opearations'];

    public function product()
    {
        return $this->hasOne(ProductMasters::class, 'id', 'product_master_id');
    }

    public function operations()
    {
        return $this->hasMany(OperationMaster::class, 'id', 'sub_product_id');
    }
}
