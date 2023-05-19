<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrasladoArticulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket',
        'descripcion',
        'cantidad',
        'ubicacion_origen',
        'ubicacion_destino',
        'usuario_id',
        'detalle_pedido_id',
        'estado_articulo_id',
        'kardex_articulos_id',
        'fecha',
        'hora'
    ];

    protected $table  = 'traslados_articulos';
}
