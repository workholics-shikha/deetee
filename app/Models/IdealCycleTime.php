<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdealCycleTime extends Model
{
    use HasFactory;

    protected $table = 'ideal_cycle_time_parent';
    protected $fillable = [
         
        'sub_product_id',
        'operation_id',
        'machine_id',
       
    ];
}
