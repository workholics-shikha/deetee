<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        "so_id",
        "so_product_qr_code",
        "drawingno",
        "product_id",
        "sub_product_id",
        "item_id",
        "item_name",
        "partno",
        "description",
        "unit",
        "size1",
        "size2",
        "size3", 
        "material",
        "hardness",
        "measureunit",
        "quantity",
        "rate",
        "poquantity",
        "porate",
        "additionaloperation",
        "remark",
        "bgroupsheet",
        "pcs",
        "total",
        "totalinr",
        "sequence",
        "cpoid",
        "totalpcs",
        "operation1",
        "operation2",
        "operation3",
        "pass_no", 
        "pass_sheet",
        "soquantity",
        "sorate",
        "cpoitemid",
        "scr_status",
        "product_status"
    ];

    public function getSoProductQrCodeAttribute($value)
    {
        return $value ? asset('storage/so-product-qrcodes/' . $value) : asset(DEFAULT_QR);
    }

    public function item()
    {
        return $this->belongsTo(ProductMasters::class, 'item_id', 'id');
    }

    public function operations()
    {
        return $this->hasMany(SubproductWiseOperation::class, 'subproduct_id', 'sub_product_id');
    }

    public function product()
    {
        return $this->belongsTo(ProductMasters::class, 'product_id', 'id');
    }

    public function subProduct()
    {
        return $this->belongsTo(SubProduct::class, 'sub_product_id');
    }

    protected $appends = ['sub_product_name'];

    public function getSubProductNameAttribute()
    {
        if (is_null($this->sub_product_id)) {
            return null;
        }

        return SubProduct::where('id', $this->sub_product_id)->value('sub_product_name');
    }
}
