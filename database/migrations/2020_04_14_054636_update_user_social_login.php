<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserSocialLogin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
            $table->string('google_id')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('twiter_id')->nullable();
            $table->string('avatar')->nullable();
            $table->string('avatar_original')->nullable();
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
            if (Schema::hasColumn('users', 'google_id')) {
                $table->dropColumn('google_id');
            }
            if (Schema::hasColumn('users', 'facebook_id')) {
                $table->dropColumn('facebook_id');
            }
            if (Schema::hasColumn('users', 'twiter_id')) {
                $table->dropColumn('twiter_id');
            }
            if (Schema::hasColumn('users', 'avatar')) {
                $table->dropColumn('avatar');
            }
            if (Schema::hasColumn('users', 'avatar_original')) {
                $table->dropColumn('avatar_original');
            }
            $table->string('password')->change();
        });
    }
}
