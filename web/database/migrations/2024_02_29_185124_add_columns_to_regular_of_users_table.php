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
            $table->boolean('became_model')->after('join_date')->nullable();
            $table->timestamp('check_date')->after('join_date')->nullable();
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
            $table->dropColumn('check_date');
            $table->dropColumn('became_model');
        });
    }
};
