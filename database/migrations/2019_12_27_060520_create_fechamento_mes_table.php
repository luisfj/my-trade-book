<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFechamentoMesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fechamento_mes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('mes_ano');
            $table->decimal('receitas')->default(0);
            $table->decimal('despesas')->default(0);
            $table->decimal('resultado_mes')->default(0);

            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('conta_fechamento_id');

            $table->timestamps();

            $table->foreign('conta_fechamento_id')->references('id')->on('conta_fechamentos');
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
        Schema::dropIfExists('fechamento_mes');
    }
}
