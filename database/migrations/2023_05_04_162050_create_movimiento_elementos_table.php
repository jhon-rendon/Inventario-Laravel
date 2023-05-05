<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientoElementosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento_elementos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->require();
            $table->string('ticket')->nullable();
            $table->text('descripcion')->nullable();
            $table->integer('user_id');
            $table->foreignId('estado_articulo_id')->references('id')->on('estado_articulo');
            $table->foreignId('ubicacion_id')->references('id')->on('ubicacion');
            $table->foreignId('detalle_elementos_id')->references('id')->on('detalle_elementos');
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
        Schema::dropIfExists('movimiento_elementos');
    }
}
