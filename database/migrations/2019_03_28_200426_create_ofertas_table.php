<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfertasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ofertas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('preco', 10, 2);
            $table->dateTime('validade');
            $table->string('observacao');
            $table->string('status')->default('ATIVO');
            $table->unsignedInteger('empresa_id')->references('id')->on('empresas');
            $table->unsignedInteger('produto_id')->references('id')->on('produtos');
            $table->unsignedInteger('cidade_id')->references('id')->on('cidades');
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
        Schema::dropIfExists('ofertas');
    }
}
