<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallePedidoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_pedido', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad_solicitada')->require();
            $table->string('descripcion')->nullable();
            $table->integer('cantidad_aprobada')->require()->default(0);
            $table->integer('estado_actual')->require();
            $table->integer('usuario_id_estado_actual')->require();

            $table->foreignId('categoria_articulos_id')->references('id')->on('categoria_articulos');
            $table->foreignId('subcategoria_articulos_id')->references('id')->on('subcategoria_articulos');
            $table->foreignId('solicitud_pedido_id')->references('id')->on('solicitud_pedido');



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
        Schema::dropIfExists('detalle_pedido');
    }
}
