<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDepositoEmContasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposito_em_contas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('tipo')->default('T');//D: Deposito; S: Saque; T: Transferencia
            $table->string('ticket')->nullable();
            $table->dateTime('data');
            $table->string('codigo_transacao')->nullable();
            $table->decimal('valor', 13, 2);
            $table->unsignedBigInteger('conta_id');
            $table->unsignedBigInteger('contraparte_id')->nullable();
            $table->timestamps();

            $table->foreign('conta_id')->references('id')->on('conta_corretoras')->onDelete('cascade');
            $table->foreign('contraparte_id')->references('id')->on('conta_corretoras')->onDelete(DB::raw('set null'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deposito_em_contas');
    }
}
