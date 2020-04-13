<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMoedasRemoveNotNullSigla extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('moedas', function (Blueprint $table) {
            $table->string('sifrao')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('moedas', function (Blueprint $table) {
            if (Schema::hasColumn('moedas', 'sifrao')) {
                $table->string('sifrao')->change();
            }
        });
    }
}
