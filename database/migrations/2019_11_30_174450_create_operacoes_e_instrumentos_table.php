<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperacoesEInstrumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instrumentos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->string('sigla');
            $table->timestamps();
        });

        Schema::create('operacoes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('account');
            $table->string('corretoranome');
            $table->string('alavancagem')->nullable();

            $table->string('ticket')->nullable();
            $table->dateTime('abertura')->nullable();
            $table->dateTime('fechamento')->nullable();
            $table->decimal('precoentrada', 13, 5)->nullable();
            $table->decimal('precosaida', 13, 5)->nullable();
            $table->string('tipo');//sell buy
            $table->decimal('lotes', 13,2);
            $table->decimal('comissao', 13, 2)->nullable();
            $table->decimal('impostos', 13, 2)->nullable();
            $table->decimal('swap', 13, 2)->nullable();
            $table->decimal('resultadobruto', 13, 2)->nullable();
            $table->decimal('resultado', 13, 2)->nullable();
            $table->integer('pips')->nullable();
            $table->boolean('importacao')->default(true);

            $table->integer('tempo_operacao_dias')->nullable();
            $table->time('tempo_operacao_horas')->nullable();

            $table->unsignedBigInteger('moeda_id');
            $table->unsignedBigInteger('instrumento_id')->nullable();
            $table->unsignedBigInteger('conta_corretora_id');
            $table->unsignedBigInteger('usuario_id');

            $table->timestamps();

            $table->foreign('moeda_id')->references('id')->on('moedas');
            $table->foreign('instrumento_id')->references('id')->on('instrumentos');
            $table->foreign('conta_corretora_id')->references('id')->on('conta_corretoras')->onDelete('cascade');
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
        Schema::dropIfExists('operacoes');
        Schema::dropIfExists('instrumentos');
    }
}
