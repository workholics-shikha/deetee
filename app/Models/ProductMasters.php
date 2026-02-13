<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMasters extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_number',
        'unit',
        'group',
        'erp_product',
        'erp_nomenclature',
        'product_modified_name',
        'product_qr_code',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function getProfileImageAttribute($value)
    {
        if ($value) {
            return asset('uploads/profile_image/'.$value);
        }

        return asset(NO_USER_IMG);
    }

    public function getProductQrCodeAttribute($value)
    {
        if ($value) {
            return asset('storage/product-qrcodes/'.$value);
        }

        return asset(DEFAULT_QR);
    }

    public function subProducts()
    {
        return $this->hasMany(SubProduct::class, 'product_master_id'); // adjust foreign key if needed
    }
}
