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
    const REEMBOLSADO = 'REEMBOLSADO';

    const PAYPAL = 'PAYPAL';
    const TARJETA = 'TARJETA';

    protected $fillable = [
        'cantidad_adultos',
        'cantidad_ninos',
        'subtotal',
        'total',
        'fecha',
        'reembolso_compra',
        'status',
        'metodo_pago',
        'user_id',
        'paquete_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function paquete(){
        return $this->belongsTo(Paquete::class);
    }
}
