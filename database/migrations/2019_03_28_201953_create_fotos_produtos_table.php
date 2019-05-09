<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFotosProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fotos_produtos', function (Blueprint $table) {
            $table->unsignedInteger('fotos_id')->references('id')->on('fotos');
            $table->unsignedInteger('produtos_id')->references('id')->on('produtos');
            $table->string('status')->default('ATIVO');
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
        Schema::dropIfExists('fotos_produtos');
    }
}
