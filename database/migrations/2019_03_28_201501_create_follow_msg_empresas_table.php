<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowMsgEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follow_msg_empresas', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->references('id')->on('users');
            $table->unsignedInteger('empresa_id')->references('id')->on('empresas');
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
        Schema::dropIfExists('follow_msg_empresas');
    }
}
