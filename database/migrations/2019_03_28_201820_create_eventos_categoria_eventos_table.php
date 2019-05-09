<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventosCategoriaEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos_categoria_eventos', function (Blueprint $table) {
            $table->unsignedInteger('evento_id')->references('id')->on('eventos');
            $table->unsignedInteger('categorias_evento_id')->references('id')->on('categorias_eventos');
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
        Schema::dropIfExists('eventos_categoria_eventos');
    }
}
