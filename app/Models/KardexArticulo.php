<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class KardexArticulo extends Model
{
    use HasFactory, LaravelVueDatatableTrait;

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


    public function subcategoria(){

        return $this->belongsTo(SubCategoriaArticulo::class,'subcategoria_articulos_id','id');
    }

    public function marcas(){

        return $this->belongsTo(Marca::class,'marcas_id','id');
    }

    public function kardexUbicacion(){

        return $this->hasMany(KardexUbicacion::class,'kardex_articulos','id');
    }

    public function detalleTrasladoArticulo(){

        return $this->hasMany(DetalleTrasladoArticulo::class,'kardex_articulos_id','id');
    }

}
