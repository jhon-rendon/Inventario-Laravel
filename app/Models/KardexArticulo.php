<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KardexArticulo extends Model
{
    use HasFactory;

    protected $fillable = [
        "modelo",
        "descripcion",
        "marcas_id",
        "serial",
        "activo",
        "ubicacion_actual",
        "estado_actual",
        "marcas_id",
        "categoria_articulos_id",
        "subcategoria_articulos_id"
    ];

    protected $table  = "kardex_articulos";
}
