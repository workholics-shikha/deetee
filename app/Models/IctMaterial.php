<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IctMaterial extends Model
{
    use HasFactory;

    protected $table = 'ict_material';

    protected $fillable = [
        'ict_id',
        'diameter_start_range',
        'diameter_end_range',
        'd2_h13',
        'd3',
        'en31',
    ];
}
