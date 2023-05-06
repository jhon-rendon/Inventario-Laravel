<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallePedidoEstadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_pedido_estados', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->require();
            $table->string('hora')->require();
            $table->integer('usuario_id')->require();
            $table->string('descripcion')->nullable();
            $table->foreignId('detalle_pedido_id')->references('id')->on('detalle_pedido');
            $table->foreignId('estados_solicitud_id')->references('id')->on('estados_solicitud');
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
        Schema::dropIfExists('detalle_pedido_estados');
    }
}
