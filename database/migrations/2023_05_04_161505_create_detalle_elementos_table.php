<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleElementosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_elementos', function (Blueprint $table) {
            $table->id();
            $table->string('serial')->nullable();
            $table->string('activo')->nullable();
            $table->integer('ubicacion_actual')->require();
            $table->integer('estado_actual')->require();
            $table->foreignId('elementos_id')->references('id')->on('elementos');
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
        Schema::dropIfExists('detalle_elementos');
    }
}
