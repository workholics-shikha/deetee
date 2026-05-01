<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Operator extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'region',
        'role',
        'group',
        'shift',
        'operator_qr_code',
    ];

    // Automatically hash passwords
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($operator) {
            $operator->password = bcrypt($operator->password);
        });
    }
}
