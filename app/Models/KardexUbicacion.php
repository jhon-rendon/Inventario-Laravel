<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KardexUbicacion extends Model
{
    use HasFactory;

    protected $fillable = ['cantidad','ubicacion_id','kardex_articulos'];
    protected $table    = 'kardex_ubicacion';

    public function ubicacion(){

        return $this->belongsTo(Ubicacion::class,'ubicacion_id','id');
    }


    /*public function tipoUbicacion(){

        //return $this->belongsTo(Ubicacion::class,'ubicacion_id','id');
        return $this->ubicacion()::belongsTo(
            TipoUbicacion::class,
            'tipo_ubicacion_id', // Llave foránea en tabla "clases" que hace referencia a "cursos"
            'id', // Llave foránea en tabla "recursos_clases" que hace referencia a "clases"
            //'id', // Llave primaria en tabla "cursos"
            //'id' // Llave primaria en tabla "recursos"
        );
    }*/


}
