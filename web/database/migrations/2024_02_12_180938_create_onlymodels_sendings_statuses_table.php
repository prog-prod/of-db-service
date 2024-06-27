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
        Schema::create('onlymodels_sendings_statuses', function (Blueprint $table) {
            $table->id();
            $table->integer('all_to_send');
            $table->integer('left_to_send');
            $table->integer('sent');
            $table->float('progress');
            $table->float('speed');
            $table->integer('updated_count');
            $table->jsonb('updated_ids');
            $table->text('errors');
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
        Schema::dropIfExists('onlymodels_sendings_statuses');
    }
};
