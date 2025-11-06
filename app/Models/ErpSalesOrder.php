<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpSalesOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        "so_id",
        "so_qr_code",
        "so_customername", 
        "so_no",
        "so_group",
        "so_unitname",
        "so_deliverytimeline",
        "so_cpodeliverytimeline",
        "so_status",
        "scr_status",
        "so_date",
        "so_unitid",
        "so_groupid",
        "soquantity"
    ];
      
    public function getSoQrCodeAttribute($value)
    {
        if ($value) {
            return asset('storage/so-qrcodes/'.$value);
        }
        return asset(DEFAULT_QR);
    }
    
    public function soProducts() {
        return $this->hasMany(SalesOrderProduct::class, 'so_id', 'so_id');
    }

    public function getIndustryAttribute()
    {
        $unitId = (int) $this->so_unitid;

        return match ($unitId) {
            1 => 'Tooling',
            2 => 'RMR',
            3 => 'TMR',
            default => 'Unknown',
        };
    }
 
}
