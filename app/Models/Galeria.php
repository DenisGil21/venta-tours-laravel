<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Galeria extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deteled_at'];

    protected $fillable = [
        'imagen'
    ];

    public function paquete(){
        return $this->belongsTo(Paquete::class);
    } 
}
