<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paquete extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_adulto',
        'precio_nino',
        'informacion',
        'caracteristicas',
        'portada',
        'empresa_id',
    ];

    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }

    public function ventas(){
        return $this->hasMany(Venta::class);
    }
}
