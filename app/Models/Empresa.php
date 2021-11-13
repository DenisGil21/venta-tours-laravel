<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'nombre'
    ];

    public function scopeNombre($query, $nombre){
        if($nombre){
            return $query->where('nombre', 'LIKE', "%$nombre%");
        }
    }

    public function paquetes(){
        return $this->hasMany(Paquete::class);
    }
}
