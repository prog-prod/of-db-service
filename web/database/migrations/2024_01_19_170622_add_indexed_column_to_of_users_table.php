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
    public function up(): void
    {
        Schema::table('of_users', function (Blueprint $table) {
            $table->boolean('is_indexed')->default(false);
            $table->dateTime('index_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('of_users', function (Blueprint $table) {
            $table->dropColumn('is_indexed');
            $table->dropColumn('index_date');
        });
    }
};
