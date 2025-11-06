<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineWiseOperation extends Model
{
    use HasFactory;

    protected $table    = 'machine_wise_operations';

    protected $fillable = ['machine_id', 'operation_id'];
 
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    
    public function operation()
    {
        return $this->belongsTo(OperationMaster::class, 'operation_id', 'id');
    }
}
