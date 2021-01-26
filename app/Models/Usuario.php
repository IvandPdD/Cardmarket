<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Usuario extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        'nombre',
        'email',
        'pass',
        'rol',
    ];
}
