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
        Schema::create('public_free_trial_subscribers', function (Blueprint $table) {
            $table->id();
            $table->integer('of_user_id');
            $table->string('email');
            $table->timestamps();
            $table->foreign('of_user_id')->references('id')->on('of_users')->onDelete('cascade');
            $table->unique(['email', 'of_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('public_free_trial_subscribers');
    }
};
