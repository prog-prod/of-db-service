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
        Schema::table('regular_of_users', function (Blueprint $table) {
            $table->dropIndex('regular_of_users__index_join_date');
            $table->index(['check_date', 'join_date']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('regular_of_users', function (Blueprint $table) {
            $table->index('regular_of_users_check_date_join_date_index');
        });
    }
};
