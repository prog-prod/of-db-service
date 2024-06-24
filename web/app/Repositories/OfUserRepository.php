<?php

namespace App\Repositories;

use App\Contracts\ElasticSearchServiceInterface;
use App\Contracts\OfUserRepositoryInterface;
use App\DTO\PaginatorDTO;
use App\Helpers\Jobs\ReindexOfUsersJob;
use App\Http\Resources\OfUserResource;
use App\Models\OfTag;
use App\Models\OfUser;
use App\Models\RegularOfUser;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OfUserRepository implements OfUserRepositoryInterface
{
    private int $time;

    public function searchOfUsers(string $text, $perPage = 50, $page = 1): PaginatorDTO
    {
        $ofUsers = OfUser::searchPaginate($text, null, $perPage, $page);
        return $this->setOfUserData($ofUsers);
    }

    public function searchOfUsersByTag(OfTag $ofTag, $perPage = 50, $page = 1): PaginatorDTO
    {
        $ofUsers = OfUser::searchPaginate($ofTag->key, null, $perPage, $page);
        return $this->setOfUserData($ofUsers);
    }

    public function searchNewestOfUsers($limit = 50, $page = 1): PaginatorDTO
    {
        $ofUsers = OfUser::searchPaginate('', function ($builder) {
            return $builder->orderBy('date_add', 'desc');
        }, $limit, $page);
        return $this->setOfUserData($ofUsers);
    }

    public function searchTopOfUsers($limit = 50, $page = 1): PaginatorDTO
    {
        $ofUsers = OfUser::searchPaginate('', function ($query) {
            return $query->orderBy('favorites_count', 'desc');
        }, perPage: $limit, page: $page);
        return $this->setOfUserData($ofUsers);
    }

    public function searchFreeOfUsers($limit = 50, $page = 1): PaginatorDTO
    {
        $ofUsers = OfUser::searchPaginate('', function ($query) use ($limit) {
            return $query->where('subscribe_price', 0);
        }, perPage: $limit, page: $page);
        return $this->setOfUserData($ofUsers);
    }

    public function searchOfUsersForAds($limit = 500): PaginatorDTO
    {
        $ofUsers =  OfUser::simpleSearch("free_trial_link: /https?.*/", null, $limit);
        return $this->setOfUserData($ofUsers);
    }

    public function searchFreeOfUsersOrderByLikes($limit = 50): PaginatorDTO
    {
        $ofUsers = OfUser::searchPaginate('', function ($query) use ($limit) {
            return $query->where('subscribe_price', 0)->orderBy('favorited_count', 'desc');
        }, perPage: $limit);
        return $this->setOfUserData($ofUsers);
    }

    public function updateOfUsers(array $users): int
    {
        $updateData = [];
        $modelsToReindex = [];

        foreach ($users as $user) {
            $data = $this->prepareDataForUpdating($user);
            if (!empty($data)) {
                $updateData[$user['id']] = $data;
                $modelsToReindex[] = $user['id'];
            }
        }

        if (!empty($updateData)) {
            $affected = $this->bulkUpdateUsers($updateData);
            $this->reindexUsers($modelsToReindex);
        }

        return $affected ?? 0;
    }

    protected function prepareDataForUpdating(array $user): array
    {
        if (isset($user['deleted']) && $user['deleted'] === 1) {
            $data = ['deleted' => 1];
        } else {
            $data = $this->prepareUserFieldData($user);
        }

        return $data;
    }

    public function prepareUserFieldData(array $user): array
    {
        $data = [];
        $lastSeen = isset($user['lastSeen']) ? Carbon::parse($user['lastSeen'])->timestamp : 0;
        $this->time = time();

        foreach ($this->getOfUsersFieldsMapping() as $key => $field) {
            if (in_array($key, ['last_seen', 'date_last_online'])) {
                $data[$key] = $lastSeen;
            } else if ($key === 'last_seen_diff') {
                $data[$key] = $this->time - $lastSeen;
            } else if ($key === 'deleted') {
                continue;
            } else if ($key === 'join_date' && isset($user[$field['only_fans_field_name']])) {
                $data[$key] = Carbon::parse($user[$field['only_fans_field_name']])->timestamp;
            } else if ($key === 'avatar_thumbs' || $key === 'avatar') {
                $data[$key] = !empty($user[$field['only_fans_field_name']]) ? $user[$field['only_fans_field_name']] : null;
            } else if (isset($user[$field['only_fans_field_name']])) {
                $data[$key] = is_array($user[$field['only_fans_field_name']]) ? json_encode($user[$field['only_fans_field_name']]) : $user[$field['only_fans_field_name']];
            } else {
                $data[$key] = $field['default'];
            }
        }
        return $data;
    }

    public function bulkUpdateUsers(array $updateData): int
    {
        $cases = [];
        $ids = [];
        $params = [];
        $idsDeleted = [];
        $resultParams = [];
        $affectedRows = 0;
        $currentTimestamp = Carbon::now()->toDateTimeString();
        foreach ($updateData as $id => $data) {
            if (count($data) === 1 && isset($data['deleted'])) {
                $idsDeleted[] = $id;
            } else {
                $ids[] = $id;
                foreach ($data as $field => $value) {
                    $cases[$field][] = "WHEN {$id} THEN ?";
                    $params[$field][] = $value;
                }
            }
        }

        if (!empty($idsDeleted)) {
            $affectedRows += DB::table('of_users')->whereIn('id', $idsDeleted)->update([
                'deleted' => 1,
                'updated_at' => $currentTimestamp
            ]);
        }

        $ids = implode(',', $ids);
        $query = "UPDATE of_users SET ";
        foreach ($cases as $field => $case) {
            $query .= "`{$field}` = CASE `id` " . implode(' ', $case) . " ELSE `{$field}` END, ";
            $resultParams = array_merge($resultParams, $params[$field]);
        }
        if (!empty($ids)) {
            $query .= "`updated_at` = CASE WHEN `id` IN ({$ids}) THEN ? ELSE `updated_at` END";
            $resultParams[] = $currentTimestamp;

            $query = DB::raw(rtrim($query, ', ') . " WHERE `id` IN ({$ids})");
            $affectedRows += DB::update($query, $resultParams);
        }

        return $affectedRows;
    }

    protected function reindexUsers(array $modelsToReindex): void
    {
        ReindexOfUsersJob::dispatch($modelsToReindex);
    }

    private function setOfUserData(PaginatorDTO $ofUsers): PaginatorDTO
    {
        $ofUsers->setData(OfUserResource::collection($ofUsers->getData()));
        return $ofUsers;
    }

    public function getTotalIndexedOfUsers(): int
    {
        $elasticService = app(ElasticSearchServiceInterface::class);

        $params = [
            'body' => [
                'query' => [
                    'match_all' => new \stdClass()
                ],
                'track_total_hits' => true
            ]
        ];

        $response = $elasticService->search($params);
        if ($response) {
            return $response->getTotal();
        }
        return 0;
    }

    public function getTotalOfUsers(): int
    {
        return OfUser::query()->count();
    }

    public function createOfUsers(array $users): bool|int
    {
        $insertedData = [];
        $modelsToReindex = [];

        foreach ($users as $user) {
            $data = $this->prepareDataForInsertion($user);
            if (!empty($data)) {
                $insertedData[] = $data;
                $modelsToReindex[] = $user['id'];
            }
        }

        if (!empty($insertedData)) {
            $affected = $this->bulkCreateUsers($insertedData);
            $this->reindexUsers($modelsToReindex);
        }

        return $affected ?? 0;
    }

    private function bulkCreateUsers(array $data): bool
    {
        return OfUser::query()->upsert($data, ['id']);
    }

    private function prepareDataForInsertion(mixed $user): array
    {
        return [
            'id' => $user['id'],
            ...$this->prepareUserFieldData($user)
        ];
    }

    private function getOfUsersFieldsMapping(): array
    {
        $time = $this->time ?? time();
        return [
            'name' => [
                'only_fans_field_name' => 'name',
                'default' => null
            ],
            'username' => [
                'only_fans_field_name' => 'username',
                'default' => null
            ],
            'about' => [
                'only_fans_field_name' => 'about',
                'default' => null
            ],
            'avatar' => [
                'only_fans_field_name' => 'avatar',
                'default' => null
            ],
            'avatar_thumbs' => [
                'only_fans_field_name' => 'avatarThumbs',
                'default' => null
            ],
            'can_add_subscriber' => [
                'only_fans_field_name' => 'canAddSubscriber',
                'default' => 0
            ],
            'can_earn' => [
                'only_fans_field_name' => 'canEarn',
                'default' => 0
            ],
            'can_pay_internal' => [
                'only_fans_field_name' => 'canPayInternal',
                'default' => 0
            ],
            'can_receive_chat_message' => [
                'only_fans_field_name' => 'canReceiveChatMessage',
                'default' => 0
            ],
            'favorited_count' => [
                'only_fans_field_name' => 'favoritedCount',
                'default' => 0
            ],
            'favorites_count' => [
                'only_fans_field_name' => 'favoritesCount',
                'default' => 0
            ],
            'has_friends' => [
                'only_fans_field_name' => 'hasFriends',
                'default' => 0
            ],
            'is_adult_content' => [
                'only_fans_field_name' => 'isAdultContent',
                'default' => 0
            ],
            'is_blocked' => [
                'only_fans_field_name' => 'isBlocked',
                'default' => 0
            ],
            'is_performer' => [
                'only_fans_field_name' => 'isPerformer',
                'default' => 0
            ],
            'is_private_restiction' => [
                'only_fans_field_name' => 'isPrivateRestriction',
                'default' => 0
            ],
            'is_verified' => [
                'only_fans_field_name' => 'isVerified',
                'default' => 0
            ],
            'join_date' => [
                'only_fans_field_name' => 'joinDate',
                'default' => $time
            ],
            'last_seen' => [
                'only_fans_field_name' => 'lastSeen',
                'default' => 0
            ],
            'location' => [
                'only_fans_field_name' => 'location',
                'default' => null
            ],
            'min_payout_summ' => [
                'only_fans_field_name' => 'minPayoutSumm',
                'default' => null
            ],
            'photos_count' => [
                'only_fans_field_name' => 'photosCount',
                'default' => 0
            ],
            'posts_count' => [
                'only_fans_field_name' => 'postsCount',
                'default' => 0
            ],
            'show_posts_in_feed' => [
                'only_fans_field_name' => 'showPostsInFeed',
                'default' => 0
            ],
            'show_subscribers_count' => [
                'only_fans_field_name' => 'showSubscribersCount',
                'default' => 0
            ],
            'subscribe_price' => [
                'only_fans_field_name' => 'subscribePrice',
                'default' => 0
            ],
            'subscription_bundles' => [
                'only_fans_field_name' => 'subscriptionBundles',
                'default' => null
            ],
            'tips_enabled' => [
                'only_fans_field_name' => 'tipsEnabled',
                'default' => 0
            ],
            'promotions' => [
                'only_fans_field_name' => 'promotions',
                'default' => null
            ],
            'tips_max' => [
                'only_fans_field_name' => 'tipsMax',
                'default' => 0
            ],
            'tips_min' => [
                'only_fans_field_name' => 'tipsMin',
                'default' => 0
            ],
            'videos_count' => [
                'only_fans_field_name' => 'videosCount',
                'default' => 0
            ],
            'website' => [
                'only_fans_field_name' => 'website',
                'default' => null
            ],
            'date_add' => [
                'only_fans_field_name' => 'date_add',
                'default' => $time
            ],
            'date_last_online' => [
                'only_fans_field_name' => 'lastSeen',
                'default' => 0
            ],
            'credits_min' => [
                'only_fans_field_name' => 'creditsMin',
                'default' => null
            ],
            'credits_max' => [
                'only_fans_field_name' => 'creditsMax',
                'default' => null
            ],
            'has_stripe' => [
                'only_fans_field_name' => 'hasStripe',
                'default' => 0
            ],
            'is_referrer_allowed' => [
                'only_fans_field_name' => 'isReferrerAllowed',
                'default' => 0
            ],
            'is_spotify_connected' => [
                'only_fans_field_name' => 'isSpotifyConnected',
                'default' => 0
            ],
            'referal_bonus_summ_for_referer' => [
                'only_fans_field_name' => 'referalBonusSummForReferer',
                'default' => 0
            ],
            'can_report' => [
                'only_fans_field_name' => 'canReport',
                'default' => 0
            ],
            'last_seen_diff' => [
                'only_fans_field_name' => 'last_seen_diff',
                'default' => 0
            ],
            'send_invites' => [
                'only_fans_field_name' => 'sendInvites',
                'default' => 0
            ],
            'can_trial_send' => [
                'only_fans_field_name' => 'canTrialSend',
                'default' => 0
            ],
            'is_friend' => [
                'only_fans_field_name' => 'isFriend',
                'default' => null
            ],
            'is_spring_connected' => [
                'only_fans_field_name' => 'isSpringConnected',
                'default' => null
            ],
            'first_published_post_date' => [
                'only_fans_field_name' => 'firstPublishedPostDate',
                'default' => null
            ],
            'has_saved_streams' => [
                'only_fans_field_name' => 'hasSavedStreams',
                'default' => null
            ],
            'finished_streams_count' => [
                'only_fans_field_name' => 'finishedStreamsCount',
                'default' => 0
            ],
            'should_show_finished_streams' => [
                'only_fans_field_name' => 'shouldShowFinishedStreams',
                'default' => null
            ],
            'has_links' => [
                'only_fans_field_name' => 'hasLinks',
                'default' => null
            ],
            'can_create_trial' => [
                'only_fans_field_name' => 'canCreateTrial',
                'default' => null
            ],
            'can_create_promotion' => [
                'only_fans_field_name' => 'canCreatePromotion',
                'default' => null
            ],
            'can_promotion' => [
                'only_fans_field_name' => 'canPromotion',
                'default' => null
            ],
            'subscribed_on_data' => [
                'only_fans_field_name' => 'subscribedOnData',
                'default' => null
            ],
            'subscribed_by_data' => [
                'only_fans_field_name' => 'subscribedByData',
                'default' => null
            ],
            'show_media_count' => [
                'only_fans_field_name' => 'showMediaCount',
                'default' => null
            ],
            'call_price' => [
                'only_fans_field_name' => 'callPrice',
                'default' => null
            ],
            'can_chat' => [
                'only_fans_field_name' => 'canChat',
                'default' => null
            ],
            'has_labels' => [
                'only_fans_field_name' => 'hasLabels',
                'default' => null
            ],
            'has_pinned_posts' => [
                'only_fans_field_name' => 'hasPinnedPosts',
                'default' => null
            ],
            'subscribers_count' => [
                'only_fans_field_name' => 'subscribersCount',
                'default' => null
            ],
            'is_real_performer' => [
                'only_fans_field_name' => 'isRealPerformer',
                'default' => null
            ],
            'medias_count' => [
                'only_fans_field_name' => 'mediasCount',
                'default' => null
            ],
            'archived_post_count' => [
                'only_fans_field_name' => 'archivedPostCount',
                'default' => null
            ],
            'private_archived_posts_count' => [
                'only_fans_field_name' => 'privateArchivedPostsCount',
                'default' => null
            ],
            'wishlist' => [
                'only_fans_field_name' => 'wishlist',
                'default' => null
            ],
            'raw_about' => [
                'only_fans_field_name' => 'rawAbout',
                'default' => null
            ],
            'subscribed_on_duration' => [
                'only_fans_field_name' => 'subscribedOnDuration',
                'default' => null
            ],
            'subscribed_on_expired_now' => [
                'only_fans_field_name' => 'subscribedOnExpiredNow',
                'default' => null
            ],
            'subscribed_on' => [
                'only_fans_field_name' => 'subscribedOn',
                'default' => null
            ],
            'current_subscribe_price' => [
                'only_fans_field_name' => 'currentSubscribePrice',
                'default' => null
            ],
            'subscribed_is_expired_now' => [
                'only_fans_field_name' => 'subscribedIsExpiredNow',
                'default' => null
            ],
            'subscribed_by_autoprolong' => [
                'only_fans_field_name' => 'subscribedByAutoprolong',
                'default' => null
            ],
            'subscribed_by_expire_date' => [
                'only_fans_field_name' => 'subscribedByExpireDate',
                'default' => null
            ],
            'subscribed_by_expire' => [
                'only_fans_field_name' => 'subscribedByExpire',
                'default' => null
            ],
            'subscribed_by' => [
                'only_fans_field_name' => 'subscribedBy',
                'default' => null
            ],
            'can_restrict' => [
                'only_fans_field_name' => 'canRestrict',
                'default' => null
            ],
            'is_restricted' => [
                'only_fans_field_name' => 'isRestricted',
                'default' => null
            ],
            'tips_min_internal' => [
                'only_fans_field_name' => 'tipsMinInternal',
                'default' => null
            ],
            'tips_text_enabled' => [
                'only_fans_field_name' => 'tipsTextEnabled',
                'default' => null
            ],
            'has_not_viewed_story' => [
                'only_fans_field_name' => 'hasNotViewedStory',
                'default' => null
            ],
            'has_scheduled_stream' => [
                'only_fans_field_name' => 'hasScheduledStream',
                'default' => null
            ],
            'has_stream' => [
                'only_fans_field_name' => 'hasStream',
                'default' => null
            ],
            'has_stories' => [
                'only_fans_field_name' => 'hasStories',
                'default' => null
            ],
            'can_comment_story' => [
                'only_fans_field_name' => 'canCommentStory',
                'default' => null
            ],
            'can_look_story' => [
                'only_fans_field_name' => 'canLookStory',
                'default' => null
            ],
            'header_thumbs' => [
                'only_fans_field_name' => 'headerThumbs',
                'default' => null
            ],
            'header_size' => [
                'only_fans_field_name' => 'headerSize',
                'default' => null
            ],
            'header' => [
                'only_fans_field_name' => 'header',
                'default' => null
            ],
            'deleted' => [
                'only_fans_field_name' => 'deleted',
                'default' => 0
            ],
        ];
    }

    public function addRegularUsers(array $users): int
    {

        $data = collect($users)
            ->filter(fn($user) => isset($user['id']) && isset($user['joinDate']))
            ->map(function ($user) {
                return [
                    'id' => $user['id'],
                    'join_date' => Carbon::parse($user['joinDate'])->getTimestamp()
                ];
            })->toArray();

        return RegularOfUser::query()->upsert($data, ['id'], ['join_date']);
    }

    public function getOfUserByUsername($username): ?OfUser
    {
        return OfUser::search("username: \"$username\"")->get()->first();
    }

    public function getOfUsersForIndexation(int $limit = 50): Collection
    {
        return OfUser::query()->has('tags')->where('is_indexed', '=', 0)->limit($limit)->get();
    }

    /**
     * @param int $limit
     * @return Collection
     */
    public function getSpecificOfUsersForIndexation(int $limit = 50): Collection
    {
        return OfUser::query()
            ->whereNotNull('free_trial_link')
            ->where('is_indexed', '=', 0)
            ->limit($limit)
            ->get();
    }

    public function getOfUsersThatWereIndexedToday(): Collection
    {
        return OfUser::query()->has('tags')
            ->where('is_indexed', '=', 1)
            ->where('index_date', '>', Carbon::today()
                ->format('Y-m-d H:i:s'))->get();
    }

    public function getSimilarOfUsers(OfUser $ofUser): Collection
    {
        $elasticService = app(ElasticSearchServiceInterface::class);
        $response = $elasticService->limit(8)->search(randomize: true);
        if ($response) {
            return $response->getItems()->map(fn($item) => new OfUser($item));
        }

        return collect();
    }

    public function getCategoryOfUsers(OfTag $tag): Collection
    {
        $elasticService = app(ElasticSearchServiceInterface::class);
        $response = $elasticService->limit(8)->search(randomize: true);
        if ($response) {
            return $response->getItems()->map(fn($item) => new OfUser($item));
        }

        return collect();
    }

    public function searchOfUserById(int $id): ?OfUser
    {
        return OfUser::search()->where('id', $id)->get()->first();
    }

    public function updateOfUsersIndexDate(array $usersIds, $index_date): int
    {
        return OfUser::query()->whereIn('id', $usersIds)->update([
            'index_date' => $index_date,
            'is_indexed' => 1
        ]);
    }

    public function getOfUsersWithTrialLinks(): Collection
    {
        return OfUser::query()->whereNotNull('free_trial_link')->limit(10000)->get();
    }
}
