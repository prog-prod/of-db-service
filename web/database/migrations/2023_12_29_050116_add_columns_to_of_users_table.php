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
            $table->boolean('is_friend')->nullable()->after('is_verified');
            $table->boolean('is_spring_connected')->nullable()->after('is_spotify_connected');
            $table->dateTime('first_published_post_date')->nullable()->after('referal_bonus_summ_for_referer');
            $table->boolean('has_saved_streams')->nullable()->after('referal_bonus_summ_for_referer');
            $table->integer('finished_streams_count')->nullable()->default(0)->after('referal_bonus_summ_for_referer');
            $table->boolean('should_show_finished_streams')->nullable()->after('referal_bonus_summ_for_referer');
            $table->boolean('has_links')->nullable()->after('has_friends');
            $table->boolean('can_create_trial')->nullable()->after('can_report');
            $table->boolean('can_create_promotion')->nullable()->after('can_report');
            $table->boolean('can_promotion')->nullable()->after('can_report');
            $table->string('subscribed_on_data')->nullable()->after('subscribe_price');
            $table->string('subscribed_by_data')->nullable()->after('subscribe_price');
            $table->boolean('show_media_count')->nullable()->after('show_subscribers_count');
            $table->integer('call_price')->nullable()->after('subscribe_price');
            $table->boolean('can_chat')->nullable()->after('can_report');
            $table->boolean('has_labels')->nullable()->after('has_friends');
            $table->boolean('has_pinned_posts')->nullable()->after('posts_count');
            $table->integer('subscribers_count')->nullable()->after('show_subscribers_count');
            $table->boolean('is_real_performer')->nullable()->after('is_performer');
            $table->integer('medias_count')->nullable()->after('photos_count');
            $table->integer('archived_post_count')->nullable()->after('posts_count');
            $table->integer('private_archived_posts_count')->nullable()->after('posts_count');
            $table->string('wishlist')->nullable()->after('website');
            $table->text('raw_about')->nullable()->after('about');
            $table->string('subscribed_on_duration')->nullable()->after('subscribe_price');
            $table->string('subscribed_on_expired_now')->nullable()->after('subscribe_price');
            $table->boolean('subscribed_on')->nullable()->after('subscribe_price');
            $table->integer('current_subscribe_price')->nullable()->after('subscribe_price');
            $table->string('subscribed_is_expired_now')->nullable()->after('subscribe_price');
            $table->string('subscribed_by_autoprolong')->nullable()->after('subscribe_price');
            $table->string('subscribed_by_expire_date')->nullable()->after('subscribe_price');
            $table->string('subscribed_by_expire')->nullable()->after('subscribe_price');
            $table->boolean('subscribed_by')->nullable()->after('subscribe_price');
            $table->boolean('can_restrict')->nullable()->after('can_report');
            $table->boolean('is_restricted')->nullable()->after('is_verified');
            $table->boolean('unprofitable')->nullable()->after('subscribe_price');
            $table->integer('tips_min_internal')->nullable()->after('tips_min');
            $table->boolean('tips_text_enabled')->nullable()->after('tips_enabled');
            $table->boolean('has_not_viewed_story')->nullable()->after('has_friends');
            $table->boolean('has_scheduled_stream')->nullable()->after('has_friends');
            $table->boolean('has_stream')->nullable()->after('has_friends');
            $table->boolean('has_stories')->nullable()->after('has_friends');
            $table->boolean('can_comment_story')->nullable()->after('can_report');
            $table->boolean('can_look_story')->nullable()->after('can_report');
            $table->jsonb('header_thumbs')->nullable()->after('avatar_thumbs');
            $table->jsonb('header_size')->nullable()->after('avatar_thumbs');
            $table->string('header')->nullable()->after('avatar_thumbs');
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
            $table->dropColumn('is_friend');
            $table->dropColumn('is_spring_connected');
            $table->dropColumn('first_published_post_date');
            $table->dropColumn('has_saved_streams');
            $table->dropColumn('finished_streams_count');
            $table->dropColumn('should_show_finished_streams');
            $table->dropColumn('has_links');
            $table->dropColumn('can_create_trial');
            $table->dropColumn('can_create_promotion');
            $table->dropColumn('can_promotion');
            $table->dropColumn('subscribed_on_data');
            $table->dropColumn('subscribed_by_data');
            $table->dropColumn('show_media_count');
            $table->dropColumn('call_price');
            $table->dropColumn('can_chat');
            $table->dropColumn('has_labels');
            $table->dropColumn('has_pinned_posts');
            $table->dropColumn('subscribers_count');
            $table->dropColumn('is_real_performer');
            $table->dropColumn('medias_count');
            $table->dropColumn('archived_post_count');
            $table->dropColumn('private_archived_posts_count');
            $table->dropColumn('wishlist');
            $table->dropColumn('raw_about');
            $table->dropColumn('subscribed_on_duration');
            $table->dropColumn('subscribed_on_expired_now');
            $table->dropColumn('subscribed_on');
            $table->dropColumn('current_subscribe_price');
            $table->dropColumn('subscribed_is_expired_now');
            $table->dropColumn('subscribed_by_autoprolong');
            $table->dropColumn('subscribed_by_expire_date');
            $table->dropColumn('subscribed_by_expire');
            $table->dropColumn('subscribed_by');
            $table->dropColumn('can_restrict');
            $table->dropColumn('is_restricted');
            $table->dropColumn('unprofitable');
            $table->dropColumn('tips_min_internal');
            $table->dropColumn('tips_text_enabled');
            $table->dropColumn('has_not_viewed_story');
            $table->dropColumn('has_scheduled_stream');
            $table->dropColumn('has_stream');
            $table->dropColumn('has_stories');
            $table->dropColumn('can_comment_story');
            $table->dropColumn('can_look_story');
            $table->dropColumn('header_thumbs');
            $table->dropColumn('header_size');
            $table->dropColumn('header');
        });
    }
};
