<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

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

    public function scopeNombre($query, $nombre){
        if($nombre){
            return $query->where('nombre', 'LIKE', "%$nombre%");
        }
    }

    public function scopeEmpresa($query, $nombre){
        if($nombre){
            return $query->whereHas('empresa', function($empresa) use ($nombre) {
                $empresa->where('nombre', 'LIKE', "%$nombre%");
            });
        }
    }

    public function getPortadaAttribute($value){
        return Request::root().'/img/'.$value;
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }

    public function ventas(){
        return $this->hasMany(Venta::class);
    }

    public function galerias(){
        return $this->hasMany(Galeria::class);
    }
}
