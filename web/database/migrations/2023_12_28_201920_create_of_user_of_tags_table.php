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
        Schema::create('of_tag_of_user', function (Blueprint $table) {
            $table->id();
            $table->integer('of_user_id'); // Ensure this matches the data type and attributes of 'id' in 'of_users'
            $table->unsignedBigInteger('of_tag_id'); // Ensure this matches the data type and attributes of 'id' in 'of_tags'
            $table->foreign('of_user_id')->references('id')->on('of_users')->onDelete('cascade');
            $table->foreign('of_tag_id')->references('id')->on('of_tags')->onDelete('cascade');
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
        Schema::dropIfExists('of_user_of_tags');
    }
};
