<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistroImportacaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registro_importacaos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('arquivo');
            $table->dateTime('data_primeiro_registro')->nullable();
            $table->dateTime('data_ultimo_registro')->nullable();
            $table->integer('numero_operacoes')->default(0);
            $table->integer('numero_transferencias')->default(0);
            $table->decimal('valor_operacoes', 13, 2)->default(0);
            $table->decimal('valor_transferencias', 13, 2)->default(0);

            $table->unsignedBigInteger('conta_corretora_id');
            $table->unsignedBigInteger('usuario_id');

            $table->timestamps();

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
        Schema::dropIfExists('registro_importacaos');
    }
}
