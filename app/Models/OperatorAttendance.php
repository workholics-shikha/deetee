<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatorAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_id',
        'start_date_time',
        'end_date_time',
    ];
}
