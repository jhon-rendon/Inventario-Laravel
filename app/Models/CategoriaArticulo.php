<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class CategoriaArticulo extends Model
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $table = "categoria_articulos";

    protected $fillable = [
        'nombre',
        'descripcion'
    ];
}
