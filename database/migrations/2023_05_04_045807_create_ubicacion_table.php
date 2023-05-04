<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUbicacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ubicacion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->require();
            $table->string('direccion')->nullable();
            $table->date('fecha_final')->nullable();

            //$table->foreignId('tipo_ubicacion_id')->constrained();
            //$table->unsignedBigInteger('tipo_ubicacion_id');
            $table->foreignId('tipo_ubicacion_id')->references('id')->on('tipo_ubicacion');
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
        Schema::dropIfExists('ubicacion');
    }
}
