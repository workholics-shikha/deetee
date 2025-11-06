<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpSpecialOperation extends Model
{
    use HasFactory;

    protected $fillable = [
        'specialoperation_id',
        'specialoperation_name',
        'specialoperation_unitid',
        'specialoperation_symbol',
        'specialoperation_status',
        'specialoperation_deleted'
    ];
}
