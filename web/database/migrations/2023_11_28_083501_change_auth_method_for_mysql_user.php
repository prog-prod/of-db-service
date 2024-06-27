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
        DB::unprepared("ALTER USER '".config('database.connections.mysql.username')."'@'localhost' IDENTIFIED WITH 'mysql_native_password' BY '".config('database.connections.mysql.password')."'");

        // Flush privileges
        DB::unprepared('FLUSH PRIVILEGES');
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
