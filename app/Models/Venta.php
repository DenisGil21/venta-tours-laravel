<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venta extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    const RESERVADO = 'RESERVADO';
    const CANCELADO = 'CANCELADO';

    protected $fillable = [
        'cliente',
        'telefono',
        'cantidad_adultos',
        'cantidad_ninos',
        'subtotal',
        'total',
        'fecha',
        'status',
        'user_id',
        'paquete_id'
    ];

    public function scopeCliente($query, $cliente){
        if ($cliente) {
            $query->where('cliente', 'LIKE', "%$cliente%");
        }
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function paquete(){
        return $this->belongsTo(Paquete::class);
    }
}
