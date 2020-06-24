<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDepositoEmCapitalAlocadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deposito_em_contas', function (Blueprint $table) {
            $table->unsignedBigInteger('conta_id')->nullable()->change();
            $table->unsignedBigInteger('capitalAlocado_id')->nullable();

            $table->foreign('capitalAlocado_id')->references('id')->on('capital_alocados')->onDelete(DB::raw('set null'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deposito_em_contas', function (Blueprint $table) {
            if (Schema::hasColumn('deposito_em_contas', 'conta_id')) {
                $table->unsignedBigInteger('conta_id')->change();
            }
            if (Schema::hasColumn('deposito_em_contas', 'capitalAlocado_id')) {
                $table->dropForeign(['capitalAlocado_id']);
                $table->dropColumn('capitalAlocado_id');
            }
        });
    }
}
