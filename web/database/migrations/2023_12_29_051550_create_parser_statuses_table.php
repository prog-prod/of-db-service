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
        Schema::create('parser_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('of_sign_version');
            $table->unsignedInteger('total_parsed');
            $table->unsignedInteger('good_parsed');
            $table->unsignedInteger('bad_parsed');
            $table->unsignedInteger('prepared_performers');
            $table->unsignedInteger('parsed_performers');
            $table->unsignedInteger('parsed_regulars');
            $table->unsignedInteger('prepared_regulars');
            $table->boolean('should_stop_parser');
            $table->unsignedInteger('not_found_sequence');
            $table->unsignedInteger('max_user_id_to_parse');
            $table->unsignedInteger('min_user_id_to_parse');
            $table->unsignedInteger('chunk_size');
            $table->unsignedInteger('speed');
            $table->float('average_response_time_sec');
            $table->float('total_progress_percent');
            $table->unsignedInteger('time_sec');
            $table->unsignedInteger('finished_chunks_count');
            $table->unsignedInteger('in_progress_chunks_count');
            $table->unsignedInteger('total_chunks_count');
            $table->unsignedInteger('used_proxies_count');
            $table->jsonb('used_proxies_list'); // Assuming this is a text field to store a list
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
        Schema::dropIfExists('parser_statuses');
    }
};
