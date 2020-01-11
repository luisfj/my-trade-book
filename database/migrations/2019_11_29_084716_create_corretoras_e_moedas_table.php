<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorretorasEMoedasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moedas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->string('sigla');
            $table->timestamps();
        });

        Schema::create('corretoras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->string('uf')->nullable();
            $table->unsignedBigInteger('moeda_id');
            $table->timestamps();

            $table->foreign('moeda_id')->references('id')->on('moedas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('corretoras');
        Schema::dropIfExists('moedas');
    }
}
