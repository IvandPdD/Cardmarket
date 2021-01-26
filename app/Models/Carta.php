<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carta extends Model
{
    use HasFactory;

    public function relacion(){
    	return $this->hasMany(RelacionColeccionCarta::class);
    }

    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}
