<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voto_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('opcao_id');
            $table->unsignedBigInteger('post_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('post_id')
                ->references('id')
                ->on('posts')
                ->onDelete('cascade');

            $table->foreign('opcao_id')
                ->references('id')
                ->on('opcao_enquetes')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('multiescolha')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voto_users');

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('multiescolha');
        });
    }
}
