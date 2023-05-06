<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKardexArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kardex_articulos', function (Blueprint $table) {
            $table->id();
            $table->string('modelo')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('serial')->nullable();
            $table->string('activo')->nullable();
            $table->integer('ubicacion_actual')->nullable();
            $table->integer('estado_actual')->nullable();

            $table->foreignId('marcas_id')->references('id')->on('marcas');
            $table->foreignId('categoria_articulos_id')->references('id')->on('categoria_articulos');
            $table->foreignId('subcategoria_articulos_id')->references('id')->on('subcategoria_articulos');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kardex_articulos');
    }
}
