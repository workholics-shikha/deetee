<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'designation',
        'unit',
        'unit_name',
        'employee_group',
        'phone',
        'role',
        'department',
        'shift',
        'user_qr_code',
        'profile_image',
        'username', 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getNameAttribute($value)
    {
        return ($value) ? ucfirst($value) : '';
    }

    public function getProfileImageAttribute($value)
    {
        if ($value) {
            return asset('uploads/profile_image/' . $value);
        }

        return asset(NO_USER_IMG);
    }

    public function getUserQrCodeAttribute($value)
    {
        if ($value) {
            return asset('storage/user-qrcodes/' . $value);
        }

        return asset(DEFAULT_QR);
    }

    public function roleName()
    {
        return $this->belongsTo(Role::class, 'role', 'id');
    }

    public function scopeAdmin($query)
    {
        $query->where('role', '1');
    }

    public function scopeOperator($query)
    {
        $query->whereIn('role', [2, 9]);
    }

    public function scopeSupervisor($query)
    {
        $query->where('role', '3');
    }

    public function scopeHOD($query)
    {
        $query->where('role', '4');
    }

    public function scopeUnitHead($query)
    {
        $query->where('role', '5');
    }

    public function scopeCEO($query)
    {
        $query->where('role', '6');
    }

    public function getUnitAttribute($value)
    {

        if ($value == 1) {
            $unit_no = 'I';
        }

        if ($value == 2) {
            $unit_no = 'II';
        }

        if ($value == 4) {
            $unit_no = 'IV';
        }

        return $value;
    }
    
}
