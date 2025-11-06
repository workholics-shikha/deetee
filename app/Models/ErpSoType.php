<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpSoType extends Model
{
    use HasFactory;

    protected $fillable = [
        'sotype_id',
        'sotype_name',
        'sotype_status',
        'sotype_deleted',
        'sotype_deletedby',
        'sotype_deletedtime'
    ];
}
