<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFotosTable extends Migration
{
    
    public function up()
    {
        Schema::create('fotos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->string('url');
            $table->string('status')->default('ATIVO');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fotos');
    }
}
