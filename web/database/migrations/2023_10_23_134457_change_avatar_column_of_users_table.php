<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('of_users', function (Blueprint $table) {
            $table->text('avatar')->nullable()->default(null)->change();
            $table->text('about')->nullable()->default(null)->change();
            $table->json('avatar_thumbs')->nullable()->default(null)->after('avatar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('of_users', function (Blueprint $table) {
            $table->dropColumn('avatarThumbs');
        });
    }
};
