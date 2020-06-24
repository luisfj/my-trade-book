<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapitalAlocadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capital_alocados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->decimal('saldo', 13, 2)->default(0);

            $table->unsignedBigInteger('moeda_id')->nullable();
            $table->unsignedBigInteger('usuario_id');

            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('moeda_id')->references('id')->on('moedas')->onDelete(DB::raw('set null'));
        });

        Schema::table('conta_corretoras', function (Blueprint $table) {
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
        Schema::table('conta_corretoras', function (Blueprint $table) {
            if (Schema::hasColumn('conta_corretoras', 'capitalAlocado_id')) {
                $table->dropForeign(['capitalAlocado_id']);
                $table->dropColumn('capitalAlocado_id');
            }
        });

        Schema::dropIfExists('capital_alocados');
    }
}
