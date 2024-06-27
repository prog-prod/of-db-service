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
        Schema::table('of_users', function (Blueprint $table) {
            $table->json('avatar')->nullable()->default(null)->change();
            $table->timestamps();
        });
        DB::table('of_users')->update(['created_at' => now()->subDays(8)->toIso8601String(), 'updated_at' => now()->subDays(8)->toIso8601String()]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('of_users', function (Blueprint $table) {
            $table->unsignedInteger('avatar')->change();
            $table->dropTimestamps();
        });
    }
};
