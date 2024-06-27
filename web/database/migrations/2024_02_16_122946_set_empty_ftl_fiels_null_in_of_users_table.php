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
            $table->string('free_trial_link')->after('can_trial_send')->nullable()->change();
        });
        DB::table('of_users')
            ->where('free_trial_link', '=', '')
            ->update(['free_trial_link' => null]);
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
