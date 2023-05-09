<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoArticulo extends Model
{
    use HasFactory;

    protected $fillable = ['estado'];
    protected $table    = "estado_articulo";
}
