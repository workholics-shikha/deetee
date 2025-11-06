<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineHealthMonitoring extends Model
{
    use HasFactory;
    protected $fillable = ['master_id', 'start_date_time', 'end_date_time', 'reason', 'monitor_for'];
}