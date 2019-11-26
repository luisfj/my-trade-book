<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('body');
            $table->char('tipo')->default('M');//M(mensagem) E(enquete)
            $table->date('data_fim_enquete')->nullable();
            $table->boolean('resultado_publico')->default(false);
            $table->boolean('exibir')->default(true);
            //$table->boolean('multiescolha')->default(false);  "esta na migration voto_user"
            $table->timestampsTz();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
