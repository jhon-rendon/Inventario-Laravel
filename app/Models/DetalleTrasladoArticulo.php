<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleTrasladoArticulo extends Model
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
        'hora',
        'traslados_articulos_id'
    ];

    protected $table  = 'detalle_traslado_articulo';

    public function articulo(){

        return $this->belongsTo(KardexArticulo::class,'kardex_articulos_id','id');
    }

    public function traslado(){

        return $this->belongsTo(TrasladoArticulo::class,'traslados_articulos_id','id');
    }

    public function estadoArticulo(){

        return $this->belongsTo(EstadoArticulo::class,'estado_articulo_id','id');
    }
}
