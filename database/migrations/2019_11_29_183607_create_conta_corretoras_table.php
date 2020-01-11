<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContaCorretorasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conta_corretoras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('identificador');
            $table->decimal('entradas', 13, 2)->default(0);
            $table->decimal('saidas', 13, 2)->default(0);
            $table->decimal('saldo', 13, 2)->default(0);
            $table->integer('operacoes_abertas')->default(0);
            $table->integer('operacoes_fechadas')->default(0);
            $table->date('dtabertura')->nullable();
            $table->boolean('ativa')->default(true);
            $table->char('tipo')->nullable();
            $table->boolean('exibirnopainel')->default(true);
            $table->unsignedBigInteger('moeda_id')->nullable();
            $table->unsignedBigInteger('corretora_id')->nullable();
            $table->unsignedBigInteger('usuario_id');
            $table->timestamps();

            $table->foreign('moeda_id')->references('id')->on('moedas');
            $table->foreign('corretora_id')->references('id')->on('corretoras');
            $table->foreign('usuario_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conta_corretoras');
    }
}
