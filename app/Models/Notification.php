<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'title',
        'message',
        'type',
        'is_read',
        'read_at',
    ];

    public function machine()
    {
        return $this->hasOne(MachineMaster::class, 'id', 'machine_id');
    }
}
