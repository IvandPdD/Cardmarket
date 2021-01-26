<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelacionColeccionCarta extends Model
{
    use HasFactory;

    public function carta(){
    	return $this->belongsTo(Carta::class);
    }

    public function coleccion(){
    	return $this->belongsTo(Coleccion::class);
    }
}
