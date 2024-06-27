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
        Schema::create('parser_chunks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from')->default(0);
            $table->unsignedBigInteger('to')->default(0);
            $table->unsignedBigInteger('progress')->default(0);
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
        Schema::dropIfExists('parser_chunks');
    }
};
