<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;

class Galeria extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deteled_at'];

    protected $fillable = [
        'imagen',
        'paquete_id'
    ];

    public function getImagenAttribute($value){
        return Request::root().'/img/'.$value;
    }

    public function paquete(){
        return $this->belongsTo(Paquete::class);
    } 
}
