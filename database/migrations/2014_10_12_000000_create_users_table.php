<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->string('sobrenome');
            $table->string('status')->default('ATIVO');
            $table->string('genero');
            $table->string('foto')->nullable();
            $table->date('nascimento')->nullable();
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->unsignedInteger('cidade_id')->references('id')->on('cidades');
            $table->timestamps();
            $table->unique(['email', 'status', 'id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
