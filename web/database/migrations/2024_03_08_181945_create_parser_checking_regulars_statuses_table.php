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
        Schema::create('parser_checking_regulars_statuses', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(true);
            $table->unsignedInteger('of_sign_version');
            $table->unsignedInteger('regulars_need_to_be_checked_count');
            $table->unsignedInteger('total_count');
            $table->unsignedInteger('good_count');
            $table->unsignedInteger('bad_count');
            $table->unsignedInteger('regulars_became_model_count');
            $table->unsignedInteger('total_progress_percent');
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
        Schema::dropIfExists('parser_checking_regulars_statuses');
    }
};
