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
            $table->jsonb('subscription_bundles')->nullable()->after('subscribe_price');
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
            $table->dropColumn('subscription_bundles');
        });
    }
};
