<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationMaster extends Model
{
    use HasFactory;

    protected $fillable = ['operation_name', 'unit', 'parameter_input', 'matrix', 'operation_qr_code'];

    public function getParametersArrayAttribute()
    {
        return array_values(array_filter(array_map('trim', preg_split('/[\n,]+/', $this->parameters))));
    }

    public function getOperationQrCodeAttribute($value)
    {
        if ($value) {
            return asset('storage/operation-qr-codes/'.$value);
        }

        return asset(DEFAULT_QR);
    }

    public function machines()
    {
        return $this->belongsToMany(MachineMaster::class, 'machine_wise_operations', 'operation_id', 'machine_id');
    }
}
