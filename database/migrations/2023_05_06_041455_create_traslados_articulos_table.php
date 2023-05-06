<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrasladosArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traslados_articulos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->require();
            $table->string('hora')->require();
            $table->string('ticket')->nullable();
            $table->string('descripcion')->nullable();
            $table->integer('cantidad')->require();
            $table->string('tipo_traslado')->nullable();
            $table->integer('ubicacion_origen')->require();
            $table->integer('ubicacion_destino')->require();
            $table->integer('usuario_id')->require();
            $table->integer('detalle_pedido_id')->nullable()->comment("Si el traslado se realiza por medio de una solicitud de pedido, en este campo se almacena el id de la solicitud o de lo contrario sera null");

            $table->foreignId('estado_articulo_id')->references('id')->on('estado_articulo');
            $table->foreignId('kardex_articulos_id')->references('id')->on('kardex_articulos');

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
        Schema::dropIfExists('traslados_articulos');
    }
}
