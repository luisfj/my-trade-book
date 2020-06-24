<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstrategiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estrategias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->string('descricao')->nullable();
            $table->boolean('ativa')->default(true);
            $table->timestamps();
            $table->unsignedBigInteger('usuario_id');

            $table->foreign('usuario_id')->references('id')->on('users');
        });

        Schema::table('operacoes', function (Blueprint $table) {
            $table->char('stop')->default('I');//Inalterado. Prolongado. Antecipado
            $table->char('alvo')->default('I');

            $table->unsignedBigInteger('estrategia_id')->nullable();

            $table->foreign('estrategia_id')->references('id')->on('estrategias')->onDelete(DB::raw('set null'));
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operacoes', function (Blueprint $table) {
            if (Schema::hasColumn('operacoes', 'estrategia_id')) {
                $table->dropForeign(['estrategia_id']);
                $table->dropColumn('estrategia_id');
            }
            if (Schema::hasColumn('operacoes', 'stop')) {
                $table->dropColumn('stop');
            }
            if (Schema::hasColumn('operacoes', 'alvo')) {
                $table->dropColumn('alvo');
            }
        });

        Schema::dropIfExists('estrategias');
    }
}
