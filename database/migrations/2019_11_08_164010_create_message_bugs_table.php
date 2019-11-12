<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageBugsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_bugs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('descricao');
            $table->date('data_resolucao')->nullable();
            $table->unsignedBigInteger('bug_id');
            $table->unsignedBigInteger('autor_id');
            $table->timestamps();

            $table->foreign('bug_id')->references('id')->on('bugs')->onDelete('cascade');
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
        Schema::dropIfExists('message_bugs');
    }
}
