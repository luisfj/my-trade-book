<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('perfil_investidor_id')->nullable();
            $table->char('sexo')->nullable();
            $table->string('nome_completo')->nullable();
            $table->date('inicio_mercado')->nullable();
            $table->date('nascimento')->nullable();
            $table->string('cpf')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('site')->nullable();
            $table->string('telefone')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->string('pais')->nullable();
            $table->string('cep')->nullable();
            $table->text('sobre_mim')->nullable();
            $table->timestamps();

            $table->foreign('perfil_investidor_id')->references('id')->on('perfil_investidors');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('profile_id')->nullable();

            $table->foreign('profile_id')->references('id')->on('profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_profile_id_foreign');
            $table->dropColumn('profile_id');
        });

        Schema::dropIfExists('profiles');
    }
}
