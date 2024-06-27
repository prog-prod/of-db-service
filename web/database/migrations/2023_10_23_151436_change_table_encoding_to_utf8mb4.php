<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        // Update table default charset and collation
        DB::statement("ALTER TABLE of_users CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        // Update specific columns
        Schema::table('of_users', function (Blueprint $table) {
            $table->text('about')->charset('utf8mb4')->collate('utf8mb4_unicode_ci')->change();
            $table->text('avatar')->charset('utf8mb4')->collate('utf8mb4_unicode_ci')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
