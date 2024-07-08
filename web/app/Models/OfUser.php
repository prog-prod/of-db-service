<?php

namespace App\Models;

use App\Casts\SubscriptionBundleCast;
use App\Contracts\SearchableModel;
use App\DTO\IncomeDTO;
use App\DTO\RangeDTO;
use App\DTO\SocialLinkDTO;
use App\DTO\SubscriptionBundleDTO;
use App\Enums\OfUserSocialNetworksEnum;
use App\Models\Traits\ElasticScoutTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;

/**
 * Class OfUser
 *
 * Model representing a user with various attributes in a social media context.
 *
 * @property int $id Unique identifier for the user.
 * @property string $name Name of the user.
 * @property string $username Username of the user.
 * @property string|null $about Brief description about the user.
 * @property string|null $avatar URL to the user's avatar image.
 * @property array|null $avatar_thumbs Thumbnails of the user's avatar image.
 * @property bool $can_add_subscriber Indicates if the user can add subscribers.
 * @property bool $can_earn Indicates if the user can earn through the platform.
 * @property bool $can_pay_internal Indicates if the user can make internal payments.
 * @property bool $can_receive_chat_message Indicates if the user can receive chat messages.
 * @property bool $can_trial_send Indicates if the user can send trial messages.
 * @property string $free_trial_link Indicates if the user can send trial messages.
 * @property int $favorited_count Count of how many times the user has been favorited.
 * @property int $favorites_count Count of favorites by the user.
 * @property bool $has_friends Indicates if the user has friends on the platform.
 * @property bool $is_adult_content Indicates if the user's content is adult-oriented.
 * @property bool $is_blocked Indicates if the user is blocked.
 * @property bool $is_performer Indicates if the user is a performer.
 * @property bool $is_private_restiction Indicates if there are any private restrictions.
 * @property bool $is_verified Indicates if the user is verified.
 * @property string|null $join_date Date when the user joined the platform.
 * @property string|null $last_seen Date when the user was last seen online.
 * @property string|null $location Location of the user.
 * @property float $min_payout_summ Minimum payout sum for the user.
 * @property int $photos_count Count of photos uploaded by the user.
 * @property int $posts_count Count of posts made by the user.
 * @property bool $show_posts_in_feed Indicates if the user's posts are shown in feeds.
 * @property bool $show_subscribers_count Indicates if the subscriber count is public.
 * @property float $subscribe_price Price of subscribing to the user's content.
 * @property bool $tips_enabled Indicates if tips are enabled for the user.
 * @property float $tips_max Maximum tip amount.
 * @property float $tips_min Minimum tip amount.
 * @property int $videos_count Count of videos uploaded by the user.
 * @property Collection<SubscriptionBundleDTO> subscription_bundles
 * @property string|null $website Website of the user.
 * @property string|null $date_add Date when the user was added to the platform.
 * @property string|null $date_last_online Date when the user was last online.
 * @property float $credits_min Minimum credits.
 * @property float $credits_max Maximum credits.
 * @property bool $has_stripe Indicates if the user is integrated with Stripe.
 * @property bool $is_referrer_allowed Indicates if the user is allowed to refer others.
 * @property bool $is_spotify_connected Indicates if the user's Spotify is connected.
 * @property float $referal_bonus_summ_for_referer Referral bonus sum for the referrer.
 * @property bool $can_report Indicates if the user can report content or other users.
 * @property string|null $last_seen_diff Difference in time since the user was last seen.
 * @property bool $send_invites Indicates if the user can send invites.
 * @property int $priority Priority level of the user.
 * @property bool $deleted Indicates if the user is marked as deleted.
 * @property string|null $date_published Date when the user was published.
 * @property bool $is_friend Indicates if the user is marked as a friend.
 * @property bool $is_spring_connected Indicates if the user is connected to Spring.
 * @property string|null $first_published_post_date Date of the first published post.
 * @property bool $has_saved_streams Indicates if the user has saved streams.
 * @property int $finished_streams_count Count of finished streams by the user.
 * @property bool $should_show_finished_streams Indicates if finished streams should be shown.
 * @property bool $has_links Indicates if the user has links.
 * @property bool $can_create_trial Indicates if the user can create trial content.
 * @property bool $can_create_promotion Indicates if the user can create promotions.
 * @property bool $can_promotion Indicates if the user can promote content.
 * @property string|null $subscribed_on_data Data about what the user is subscribed to.
 * @property string|null $subscribed_by_data Data about who is subscribed to the user.
 * @property bool $show_media_count Indicates if the media count is shown.
 * @property float $call_price Price for calls with the user.
 * @property bool $can_chat Indicates if the user can engage in chats.
 * @property bool $has_labels Indicates if the user has labels.
 * @property bool $has_pinned_posts Indicates if the user has pinned posts.
 * @property int $subscribers_count Count of the user's subscribers.
 * @property bool $is_real_performer Indicates if the user is a real performer.
 * @property int $medias_count Count of media items uploaded by the user.
 * @property int $archived_post_count Count of archived posts by the user.
 * @property int $private_archived_posts_count Count of private archived posts by the user.
 * @property string|null $wishlist Wishlist of the user.
 * @property string|null $raw_about Raw information about the user.
 * @property string|null $subscribed_on_duration Duration of current subscription.
 * @property bool $subscribed_on_expired_now Indicates if the current subscription has expired.
 * @property string|null $subscribed_on Date of current subscription.
 * @property float $current_subscribe_price Current subscription price.
 * @property bool $subscribed_is_expired_now Indicates if the subscription is currently expired.
 * @property bool $subscribed_by_autoprolong Indicates if the subscription is set to auto-renew.
 * @property string|null $subscribed_by_expire_date Expiration date of the subscription.
 * @property string|null $subscribed_by_expire Indicates if the subscription is about to expire.
 * @property string|null $subscribed_by Information about who subscribed to the user.
 * @property bool $can_restrict Indicates if the user can restrict others.
 * @property bool $is_restricted Indicates if the user is restricted.
 * @property bool $unprofitable Indicates if the user is unprofitable.
 * @property float $tips_min_internal Minimum internal tip amount.
 * @property bool $tips_text_enabled Indicates if text for tips is enabled.
 * @property bool $has_not_viewed_story Indicates if there are stories not viewed by the user.
 * @property bool $has_scheduled_stream Indicates if the user has a scheduled stream.
 * @property bool $has_stream Indicates if the user currently has a stream.
 * @property bool $has_stories Indicates if the user has stories.
 * @property bool $can_comment_story Indicates if the user can comment on stories.
 * @property bool $can_look_story Indicates if the user can view stories.
 * @property string|null $header_thumbs Thumbnails of the user's header image.
 * @property string|null $header_size Size of the user's header image.
 * @property string|null $header URL to the user's header image.
 * @property int|null $pre_tax_year
 * @property int|null $pre_tax_month
 * @property int|null $month_income_from
 * @property int|null $month_income_to
 * @property int|null $year_income_from
 * @property int|null $year_income_to
 * @property int|null $six_month_subscription_cost
 * @property int|null $year_subscription_cost
 * @property int $estimated_subscribers_count
 * @property RangeDTO $estimated_subscribers_count_range
 * @property ?OfTag $category
 * @property int $formatted_subscribers_count
 * @property bool $is_estimated_sub_count
 * @property bool $has_income
 * @property IncomeDTO $estimated_income
 * @property IncomeDTO $estimated_month_income
 * @property IncomeDTO $estimated_year_income
 * @property bool $is_free
 * @property bool $is_indexed
 * @property Carbon $index_date
 * @property int $days_from_join_date
 * @mixin Model
 * @package App\Models
 */
class OfUser extends Model implements SearchableModel
{
    use Searchable;
    use ElasticScoutTrait;

    public string $defaultAvatar = '/images/default-avatar.png';
    public ?OfTag $categorySelected = null;
    const COMMISSION_RATE = 0.2;
    const SHORT_ABOUT_LENGTH = 330;

    protected $fillable = [
        'id',
        'name',
        'username',
        'about',
        'raw_about',
        'can_earn',
        'avatar',
        'avatar_thumbs',
        'can_add_subscriber',
        'can_pay_internal',
        'can_receive_chat_message',
        'can_trial_send',
        'free_trial_link',
        'favorited_count',
        'favorites_count',
        'has_friends',
        'is_adult_content',
        'is_blocked',
        'is_performer',
        'is_private_restiction',
        'is_verified',
        'join_date',
        'last_seen',
        'location',
        'min_payout_summ',
        'photos_count',
        'posts_count',
        'show_posts_in_feed',
        'show_subscribers_count',
        'subscribe_price',
        'tips_enabled',
        'tips_max',
        'tips_min',
        'videos_count',
        'website',
        'date_add',
        'date_last_online',
        'credits_min',
        'credits_max',
        'has_stripe',
        'is_referrer_allowed',
        'is_spotify_connected',
        'referal_bonus_summ_for_referer',
        'can_report',
        'last_seen_diff',
        'send_invites',
        'priority',
        'deleted',
        'date_published',
        'is_friend',
        'is_spring_connected',
        'first_published_post_date',
        'has_saved_streams',
        'finished_streams_count',
        'should_show_finished_streams',
        'has_links',
        'can_create_trial',
        'can_create_promotion',
        'can_promotion',
        'subscribed_on_data',
        'subscribed_by_data',
        'show_media_count',
        'call_price',
        'can_chat',
        'has_labels',
        'has_pinned_posts',
        'subscribers_count',
        'is_real_performer',
        'medias_count',
        'archived_post_count',
        'private_archived_posts_count',
        'wishlist',
        'subscribed_on_duration',
        'subscribed_on_expired_now',
        'subscribed_on',
        'current_subscribe_price',
        'subscribed_is_expired_now',
        'subscribed_by_autoprolong',
        'subscribed_by_expire_date',
        'subscribed_by_expire',
        'subscription_bundles',
        'subscribed_by',
        'can_restrict',
        'promotions',
        'is_restricted',
        'unprofitable',
        'tips_min_internal',
        'tips_text_enabled',
        'has_not_viewed_story',
        'has_scheduled_stream',
        'has_stream',
        'has_stories',
        'can_comment_story',
        'can_look_story',
        'header_thumbs',
        'header_size',
        'header',
        'is_indexed',
        'index_date',
        'updated_at',
    ];
    protected $casts = [
        'avatar_thumbs' => 'json',
        'subscription_bundles' => SubscriptionBundleCast::class,
        'promotions' => 'json',
        'date_published' => 'datetime',
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
        'index_date' => 'datetime'
    ];

    public function getRouteKeyName(): string
    {
        return 'username';
    }

    public function shouldBeSearchable(): bool
    {
        $hasValidAvatar = $this->avatar !== null && $this->avatar !== '0' && $this->avatar !== '' && $this->avatar !== '1';
        return $this->priority > 0 && !$this->deleted && $hasValidAvatar;
    }

    public function toSearchableArray(): array
    {
        $array = $this->toArray();

        $searchableFields = [
            'id',
            'name',
            'username',
            'about',
            'raw_about',
            'avatar',
            'avatar_thumbs',
            'short_about',
            'favorited_count',
            'favorites_count',
            'is_verified',
            'join_date',
            'location',
            'photos_count',
            'posts_count',
            'subscribers_count',
            'subscribe_price',
            'subscription_bundles',
            'free_trial_link',
            'subscribe_price',
            'date_published',
            'website',
            'priority',
            'date_add',
            'favorites_count',
            'videos_count',
            'updated_at',
            'created_at',
            'deleted',
            'tags'
        ];

        $array['tags'] = $this->tags->pluck('id')->toArray();

        return array_intersect_key($array, array_flip($searchableFields));
    }

    // Attributes

    public function getTransformedAboutAttribute(): ?string
    {
        if ($this->about) {
            return transform_about_text($this->about);
        }

        return '';
    }

    public function getShortAboutAttribute()
    {
        // Ensure the transformed about attribute is valid UTF-8
        $text = $this->getTransformedAboutAttribute();

        if (mb_strlen($text) > 330) {
            $text = mb_substr($text, 0, 330) . '...';
        }

        return rm_tags($text);
    }

    public function getWebsiteComputedAttribute()
    {
        return $this->website ? route('users-of.redirect-to-external', ['url' => $this->website]) : null;
    }

    public function getIsEstimatedSubCountAttribute(): bool
    {
        return is_null($this->subscribers_count);
    }

    public function getFormattedSubscribersCountAttribute(): int|string
    {
        if ($this->is_estimated_sub_count && $this->estimated_subscribers_count === 0) {
            return 'unknown';
        }
        return $this->is_estimated_sub_count ? '~' . number_format($this->estimated_subscribers_count) : number_format($this->estimated_subscribers_count);
    }

    public function getEstimatedSubscribersCountAttribute(): int
    {
        $sub_price = $this->subscribe_price ?? 0;
        $photos_count = $this->photos_count ?? 0;
        $videos_count = $this->videos_count ?? 0;
        $favorited_count = $this->favorited_count ?? 0;

        if ($this->is_estimated_sub_count) {
            $result = 2935.3774 - 31.8674 * $videos_count - 2.9761 * $photos_count + 0.2793 * $favorited_count - 232.4077 * $sub_price;
            return max((int)$result, 0);
        }

        return $this->subscribers_count;
    }

    public function getEstimatedSubscribersCountRangeAttribute(): RangeDTO
    {
        $error = 4359;
        $estimated_sub_count = $this->estimated_subscribers_count;
        return new RangeDTO(from: $estimated_sub_count - $error, to: $estimated_sub_count + $error);
    }

    public function getEstimatedIncomeAttribute(): IncomeDTO
    {
        $sub_price = $this->subscribe_price ?? 0;
        $photos_count = $this->photos_count ?? 0;
        $videos_count = $this->videos_count ?? 0;
        $favorited_count = $this->favorited_count ?? 0;

        $income = -8166.4164 + 1164.5384 * $sub_price + 3.2063 * $this->estimated_subscribers_count + 1005.4089
            * $videos_count - 4.4119 * $photos_count + 1.5745 * $favorited_count;

        $error = $income * 0.1;
        if ($income <= 0) {
            return new IncomeDTO(from: 0, to: 0, income: 0);
        }
        return new IncomeDTO(from: $income - $error, to: $income + $error, income: $income);
    }

    public function getEstimatedMonthIncomeAttribute(): IncomeDTO
    {
        $count_days = $this->days_from_join_date;
        $days_in_month = min($count_days, 30);
        $from = $this->estimated_income->from / $count_days * $days_in_month;
        $to = $this->estimated_income->to / $count_days * $days_in_month;
        $income = $this->estimated_income->income / $count_days * $days_in_month;
        return new IncomeDTO(from: $from, to: $to, income: $income);
    }

    public function getEstimatedYearIncomeAttribute(): IncomeDTO
    {
        $count_month = $this->days_from_join_date / 30 > 1 ? $this->days_from_join_date / 30 : 1;
        $month_in_year = min($count_month, 12);
        return new IncomeDTO(from: $this->estimated_month_income->from * $month_in_year, to: $this->estimated_month_income->to * $month_in_year, income: $this->estimated_month_income->income * $month_in_year);
    }

    public function getHasIncomeAttribute(): bool
    {
        return $this->estimated_income->income > 0;
    }

    public function getDaysFromJoinDateAttribute(): int
    {
        $diffInSeconds = time() - $this->join_date;
        $days = $diffInSeconds / 86400;

        return floor($days);
    }

    public function getPreTaxYearAttribute(): float
    {
        $taxes = $this->estimated_year_income->to * self::COMMISSION_RATE;
        return $this->estimated_year_income->to - $taxes;
    }

    public function getPreTaxMonthAttribute(): float
    {
        $taxes = $this->estimated_month_income->to * self::COMMISSION_RATE;
        return $this->estimated_month_income->to - $taxes;
    }

    public function getIsFreeAttribute(): bool
    {
        return is_null($this->subscribe_price) || $this->subscribe_price == 0;
    }

    public function getSixMonthSubscriptionCostAttribute(): int
    {
        $subscription_bundle = $this->subscription_bundles->filter(fn(SubscriptionBundleDTO $bundle) => $bundle->duration == 6);
        if ($subscription_bundle->isNotEmpty()) {
            return $subscription_bundle->first()->price;
        }
        return 0;
    }

    public function getYearSubscriptionCostAttribute(): int
    {
        $subscription_bundle = $this->subscription_bundles->filter(fn(SubscriptionBundleDTO $bundle) => $bundle->duration == 12);
        if ($subscription_bundle->isNotEmpty()) {
            return $subscription_bundle->first()->price;
        }
        return 0;
    }

    public function getCategoryAttribute()
    {
        if (!$this->categorySelected) {
            $this->categorySelected = $this->tags->shuffle()->first();
        }
        return $this->categorySelected;
    }

    public function getStrippedAboutAttribute(): string
    {
        return rm_tags($this->about);
    }


    /**
     * @return SocialLinkDTO[]
     */
    public function getSocialLinks(): array
    {
        $social_links = [];
        if (!is_null($this->website)) {
            $network = OfUserSocialNetworksEnum::getNetworkByUrl($this->website);
            if ($network) {
                $social_links[] = new SocialLinkDTO(name: $network->value, url: route('users-of.redirect-to-external', ['url' => $this->website]));
            }
        }
        if ($this->hasLinksInAbout()) {
            foreach ($this->searchLinksInAbout() as $link) {
                $network = OfUserSocialNetworksEnum::getNetworkByUrl($link);
                if ($network) {
                    $social_links[] = new SocialLinkDTO(name: $network->value, url: route('users-of.redirect-to-external', ['url' => $link]));
                }
            }
        }

        return $social_links;
    }

    public function hasLinksInAbout(): bool
    {
        return has_links($this->about);
    }

    /**
     * @return String[]
     */
    public function searchLinksInAbout(): array
    {
        preg_match_all('/<a href="[^"]*">(https?:\/\/[^<\s]+)<\/a>/', $this->about, $matches);
        return $matches[1];
    }

    // Relations
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(OfTag::class, 'of_tag_of_user', 'of_user_id', 'of_tag_id')->where('hidden', 0);
    }
}
