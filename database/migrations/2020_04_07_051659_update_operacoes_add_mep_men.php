<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOperacoesAddMepMen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('operacoes', function (Blueprint $table) {
            $table->decimal('mep', 13, 2)->nullable();
            $table->decimal('men', 13, 2)->nullable();
            $table->decimal('pips', 13, 2)->nullable()->change();
            $table->unsignedBigInteger('registro_importacao_id')->nullable();
            $table->foreign('registro_importacao_id')->references('id')->on('registro_importacaos')->onDelete('cascade');
        });

        Schema::table('deposito_em_contas', function (Blueprint $table) {
             $table->unsignedBigInteger('registro_importacao_id')->nullable();
             $table->foreign('registro_importacao_id')->references('id')->on('registro_importacaos')->onDelete('cascade');
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
            if (Schema::hasColumn('operacoes', 'mep')) {
                $table->dropColumn('mep');
            }
            if (Schema::hasColumn('operacoes', 'men')) {
                $table->dropColumn('men');
            }
            if (Schema::hasColumn('operacoes', 'registro_importacao_id')) {
                $table->dropForeign(['registro_importacao_id']);
                $table->dropColumn('registro_importacao_id');
            }
            $table->integer('pips')->nullable()->change();
        });

        Schema::table('deposito_em_contas', function (Blueprint $table) {
            if (Schema::hasColumn('deposito_em_contas', 'registro_importacao_id')) {
                $table->dropForeign(['registro_importacao_id']);
                $table->dropColumn('registro_importacao_id');
            }
        });

    }
}
