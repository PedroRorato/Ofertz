<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnfollowMsgCategoriasProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unfollow_msg_categorias_produtos', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->references('id')->on('users');
            $table->unsignedInteger('categorias_produtos_id')->references('id')->on('categorias_produtos');
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
        Schema::dropIfExists('unfollow_msg_categorias_produtos');
    }
}
