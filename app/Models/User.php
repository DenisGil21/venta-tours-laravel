<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    
    const ADMIN_ROLE='ADMIN_ROLE';
    const USER_ROLE='USER_ROLE';

    use HasFactory, Notifiable, SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function scopeFirst_Name($query, $first_name){
        if($first_name){
            return $query->orWhere('first_name', 'LIKE', "%$first_name%");
        }
    }

    public function scopeLast_Name($query, $last_name){
        if($last_name){
            return $query->orWhere('last_name', 'LIKE', "%$last_name%");
        }
    }

    public function ventas(){
        return $this->hasMany(Venta::class);
    }

    public function isAdministrador(){
        return $this->role==User::ADMIN_ROLE;
    }
}
