<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoUbicacion extends Model
{
    use HasFactory;

    protected $fillable = ['tipo'];
    protected $table    = 'tipo_ubicacion';

    public function ubicacion(){

        return $this->hasMany(Ubicacion::class);
    }
}
