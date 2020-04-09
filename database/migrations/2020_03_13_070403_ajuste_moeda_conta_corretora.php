<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AjusteMoedaContaCorretora extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('moedas', function (Blueprint $table) {
            $table->string('sifrao');
        });
        Schema::table('conta_corretoras', function (Blueprint $table) {
            $table->boolean('padrao')->default(false);
            $table->char('real_demo')->default('D');
        });
        Schema::table('corretoras', function (Blueprint $table) {
            $table->unsignedBigInteger('usuario_id')->nullable();
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
        Schema::table('conta_corretoras', function (Blueprint $table) {
            if (Schema::hasColumn('conta_corretoras', 'real_demo')) {
                $table->dropColumn('real_demo');
            }
            if (Schema::hasColumn('conta_corretoras', 'real_demo')) {
                $table->dropColumn('padrao');
            }
        });

        Schema::table('moedas', function (Blueprint $table) {
            if (Schema::hasColumn('moedas', 'sifrao')) {
                $table->dropColumn('sifrao');
            }
        });
        Schema::table('corretoras', function (Blueprint $table) {
            if (Schema::hasColumn('corretoras', 'usuario_id')) {
                $table->dropForeign(['usuario_id']);
                $table->dropColumn('usuario_id');
            }
        });
    }
}
