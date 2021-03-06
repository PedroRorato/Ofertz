<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->string('sobrenome');
            $table->string('status')->default('ATIVO');
            $table->string('genero');
            $table->string('telefone');
            $table->string('foto')->nullable();
            $table->date('nascimento')->nullable();
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('empresa');
            $table->string('cnpj')->nullable();
            $table->string('descricao')->nullable();
            $table->unsignedInteger('cidade_id')->references('id')->on('cidades');
            $table->timestamps();
            $table->unique(['email', 'status', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}
