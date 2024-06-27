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
        Schema::create('parser_updating_statuses', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(true);
            $table->unsignedInteger('of_sign_version');
            $table->unsignedInteger('total_count');
            $table->unsignedInteger('good_count');
            $table->unsignedInteger('bad_count');
            $table->unsignedInteger('prepared_performers');
            $table->unsignedInteger('updated_performers');
            $table->unsignedInteger('used_proxies');
            $table->unsignedInteger('speed');
            $table->unsignedInteger('time_sec');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parser_updating_statuses');
    }
};
