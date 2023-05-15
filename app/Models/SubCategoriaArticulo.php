<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class SubCategoriaArticulo extends Model
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $table = "subcategoria_articulos";

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo_cantidad',
        'categoria_articulos_id'
    ];

    public function categoria(){

      return $this->belongsTo(CategoriaArticulo::class,'categoria_articulos_id','id');
    }
}
