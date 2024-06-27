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
        Schema::create('of_tags_of_tags_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('of_tag_id');
            $table->unsignedBigInteger('of_tags_group_id');

            $table->foreign('of_tag_id')->references('id')->on('of_tags')->onDelete('cascade');
            $table->foreign('of_tags_group_id')->references('id')->on('of_tags_groups')->onDelete('cascade');
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
        Schema::dropIfExists('of_tags_of_tags_groups');
    }
};
