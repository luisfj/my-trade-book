<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugToAllTargets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('slug')->nullable();
        });

        Schema::table('moedas', function (Blueprint $table) {
            $table->string('slug')->nullable();
        });

        Schema::table('instrumentos', function (Blueprint $table) {
            $table->string('slug')->nullable();
        });

        Schema::table('corretoras', function (Blueprint $table) {
            $table->string('slug')->nullable();
        });

        Schema::table('conta_corretoras', function (Blueprint $table) {
            $table->string('slug')->nullable();
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
            if (Schema::hasColumn('users', 'slug')) {
                $table->dropColumn('slug');
            }
        });

        Schema::table('moedas', function (Blueprint $table) {
            if (Schema::hasColumn('moedas', 'slug')) {
                $table->dropColumn('slug');
            }
        });

        Schema::table('instrumentos', function (Blueprint $table) {
            if (Schema::hasColumn('instrumentos', 'slug')) {
                $table->dropColumn('slug');
            }
        });

        Schema::table('corretoras', function (Blueprint $table) {
            if (Schema::hasColumn('corretoras', 'slug')) {
                $table->dropColumn('slug');
            }
        });

        Schema::table('conta_corretoras', function (Blueprint $table) {
            if (Schema::hasColumn('conta_corretoras', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
}
