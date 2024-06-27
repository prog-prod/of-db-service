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
        // required columns: name, id, join_date, date_add, last_seen_diff
        Schema::table('of_users', function (Blueprint $table) {
            $table->string('username', 100)->default(null)->change();
            $table->text('about')->default(null)->change();
            $table->text('avatar')->default(null)->change();
            $table->text('avatar_thumbs')->default(null)->change();
            $table->string('location', 100)->default(null)->change();
            $table->string('website', 100)->default(null)->change();

            // Set default values for boolean fields
            $table->boolean('can_add_subscriber')->default(0)->change();
            $table->boolean('can_earn')->default(0)->change();
            $table->boolean('can_pay_internal')->default(0)->change();
            $table->boolean('can_receive_chat_message')->default(0)->change();
            $table->boolean('can_trial_send')->default(0)->change();
            $table->boolean('has_friends')->default(0)->change();
            $table->boolean('is_adult_content')->default(0)->change();
            $table->boolean('is_blocked')->default(0)->change();
            $table->boolean('is_performer')->default(0)->change();
            $table->boolean('is_private_restiction')->default(0)->change();
            $table->boolean('is_verified')->default(0)->change();
            $table->boolean('show_posts_in_feed')->default(0)->change();
            $table->boolean('show_subscribers_count')->default(0)->change();
            $table->boolean('tips_enabled')->default(0)->change();
            $table->boolean('has_stripe')->default(0)->change();
            $table->boolean('is_referrer_allowed')->default(0)->change();
            $table->boolean('is_spotify_connected')->default(0)->change();
            $table->boolean('can_report')->default(0)->change();
            $table->boolean('deleted')->default(0)->change();

            // Set default values for integer fields
            $table->integer('favorited_count')->default(0)->change();
            $table->integer('favorites_count')->default(0)->change();
            $table->integer('photos_count')->unsigned()->default(0)->change();
            $table->integer('posts_count')->unsigned()->default(0)->change();
            $table->integer('videos_count')->unsigned()->default(0)->change();
            $table->integer('tips_max')->unsigned()->default(0)->change();
            $table->integer('tips_min')->unsigned()->default(0)->change();
            $table->integer('referal_bonus_summ_for_referer')->unsigned()->default(0)->change();
            $table->integer('send_invites')->unsigned()->default(0)->change();
            $table->integer('credits_min')->unsigned()->default(null)->change();
            $table->integer('credits_max')->unsigned()->default(null)->change();
            $table->integer('date_last_online')->unsigned()->default(0)->change();
            $table->integer('date_add')->unsigned()->change();
            $table->integer('last_seen')->unsigned()->default(0)->change();
            $table->integer('last_seen_diff')->unsigned()->default(0)->change();

            // Set default values for other types of fields
            $table->double('subscribe_price')->unsigned()->default(0)->change();
        });

        DB::statement('ALTER TABLE of_users MODIFY COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        DB::statement('ALTER TABLE of_users MODIFY COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
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
