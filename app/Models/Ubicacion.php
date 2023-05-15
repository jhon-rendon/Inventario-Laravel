<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use HasFactory;

    protected $table = "ubicacion";

    protected $fillable = ['nombre','codigo','direccion','tipo_ubicacion_id'];

    public function tipoUbicacion(){

        return $this->belongsTo(TipoUbicacion::class,'tipo_ubicacion_id','id');
    }
}
