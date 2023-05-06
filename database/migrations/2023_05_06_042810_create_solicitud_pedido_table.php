<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudPedidoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud_pedido', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->require();
            $table->string('hora')->require();
            $table->integer('usuario_id')->require();
            $table->foreignId('ubicacion_id')->references('id')->on('ubicacion');
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
        Schema::dropIfExists('solicitud_pedido');
    }
}
