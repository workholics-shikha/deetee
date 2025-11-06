<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineMaster extends Model
{
    use HasFactory;

    protected $table = 'machine_master';

    protected $fillable = [
        'unit_number',
        'unit_name',
        'machine',
        'machine_type',
        'section',
        'sub_section', 
        'operator_id',
        'tracking_id',
        'machine_qr_code',
        'machine_status',
        'machine_image'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function getMachineImageAttribute($value)
    {
        if ($value) {
            return asset('uploads/machine_image/'. $value);
        }
        return asset(NO_MACHINE_IMG);
    }

    public function getMachineQrCodeAttribute($value)
    {
        if ($value) {
            return asset('storage/machine-qrcodes/' . $value);
        }
        return asset(DEFAULT_QR);
    }
    
    public function operations()
    {
        return $this->belongsToMany(OperationMaster::class, 'machine_wise_operations', 'machine_id', 'operation_id');
    }
    
    public function getOperationListAttribute()
    {
        return $this->operations->pluck('operation_name')->implode(', ');
    }
 
}
