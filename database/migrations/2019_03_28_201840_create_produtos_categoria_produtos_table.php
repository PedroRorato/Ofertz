<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdutosCategoriaProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos_categoria_produtos', function (Blueprint $table) {
            $table->unsignedInteger('produto_id')->references('id')->on('produtos');
            $table->unsignedInteger('categorias_produto_id')->references('id')->on('categorias_produtos');
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
        Schema::dropIfExists('produtos_categoria_produtos');
    }
}
