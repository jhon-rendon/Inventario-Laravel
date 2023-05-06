<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKardexUbicacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kardex_ubicacion', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad')->require()->default(1);
            $table->foreignId('ubicacion_id')->references('id')->on('ubicacion');
            $table->foreignId('kardex_articulos')->references('id')->on('kardex_articulos');
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
        Schema::dropIfExists('kardex_ubicacion');
    }
}
