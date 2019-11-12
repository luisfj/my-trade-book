<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBugsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bugs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pagina')->nullable();
            $table->string('tipo');
            $table->text('descricao');
            $table->date('data_resolucao')->nullable();
            $table->date('data_verificacao')->nullable();
            $table->unsignedBigInteger('autor_id');
            $table->timestamps();

            $table->foreign('autor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bugs');
    }
}
