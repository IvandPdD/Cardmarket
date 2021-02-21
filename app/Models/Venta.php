<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    public function carta(){
    	return $this->belongsTo(Carta::class);
    }

    public function usuario(){
    	return $this->belongsTo(Usuario::class);
    }


}
