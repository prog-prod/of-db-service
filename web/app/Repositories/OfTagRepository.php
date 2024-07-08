<?php

namespace App\Repositories;

use App\Contracts\OfTagRepositoryInterface;
use App\Models\OfTag;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class OfTagRepository implements OfTagRepositoryInterface
{

    public function getTagsWithHighTraffic(int $count = 30): Collection
    {
        return OfTag::query()->select('key', 'name', 'traffic')->orderBy('traffic','desc')->take($count)->get();
    }

    public function getAllTags(): Collection
    {
        return OfTag::all();
    }

    public function getRandomTags(int $count = 20, array $exceptKeys = []): Collection|array
    {
        return OfTag::query()
            ->select('key','name')
            ->whereNotIn('key', $exceptKeys)
            ->limit($count)
            ->inRandomOrder()
            ->get();
    }

    public function getTotalOfTags()
    {
        return OfTag::query()->count();
    }

    public function getTotalIndexedOfTags()
    {
        $client = ClientBuilder::create()->setHosts(config('scout.elasticsearch.hosts'))->build();
        $model = new OfTag();

        $params = [
            'index' => $model->searchableAs(),
            'body' => [
                'query' => [
                    'match_all' => new \stdClass()
                ],
                'track_total_hits' => true
            ]
        ];

        try {
            $response = $client->search($params);
            return $response['hits']['total']['value'];
        } catch (\Exception $e) {
            // Обработка исключений
            return 0;
        }
    }

    public function getPopularTags(): Collection|array
    {
        return OfTag::query()
            ->select('key', 'name', 'traffic')
            ->orderBy('traffic', 'desc')
            ->whereIn('key', [
            'free-bbw',
            'asian',
            'coshott234',
            'boise-idaho',
            'paraguay',
            'vermont',
            'asheville',
            'visalia',
            'bolivia'
        ])->get();
    }

    public function getSocialNetworkTags(): Collection|array
    {
        return OfTag::query()->whereHas('groups', function ($query) {
            return $query->where('key', 'social_network');
        })->select('key', 'name')->get();
    }

    public function getCountryTags(): Collection|array
    {
        return OfTag::query()->whereHas('groups', function ($query) {
            return $query->where('key', 'country');
        })->select('key', 'name')->get();
    }

    public function getActionTags(): Collection|array
    {
        return OfTag::query()->whereHas('groups', function ($query) {
            return $query->where('key', 'action');
        })->select('key', 'name')->get();
    }

    public function getTagByKey(string $tag_key)
    {
        return OfTag::query()->where('key', $tag_key)->first();
    }

    public function getTagById(int $id): Model|null
    {
        return OfTag::query()->find($id);
    }

    public function getOfTagUsers(string $tag_key)
    {
        return $this->getTagByKey($tag_key)?->users ?? [];
    }
}
