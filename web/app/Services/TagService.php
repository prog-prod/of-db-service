<?php

namespace App\Services;

use App\Contracts\OfTagRepositoryInterface;
use App\Contracts\TagServiceInterface;
use App\DTO\NavbarDropdownDTO;
use App\Http\Resources\OfTagResource;
use App\Models\OfTag;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class TagService implements TagServiceInterface
{

    /**
     * @var OfTagRepositoryInterface|(OfTagRepositoryInterface&Application)|Application|mixed
     */
    private OfTagRepositoryInterface $tagRepository;

    public function __construct()
    {
        $this->tagRepository = app(OfTagRepositoryInterface::class);
    }

    public function getCategoriesForMainPage(): AnonymousResourceCollection
    {
        $tagsWithHighTraffic = Cache::remember('tagsWithHighTrafficMainPage', 60 * 60 * 24 * 2, function () {
            return $this->tagRepository->getTagsWithHighTraffic(count: 15);
        });

        $randomTags = Cache::remember('randomCategoriesMainPage', 60 * 5, function () use ($tagsWithHighTraffic) {
            return $this->tagRepository->getRandomTags(count: 35, exceptKeys: $tagsWithHighTraffic->pluck('key')->toArray());
        });

        return OfTagResource::collection($tagsWithHighTraffic->concat($randomTags));
    }

    public function getCategoriesForSearchPage(): AnonymousResourceCollection
    {
        $tagsWithHighTraffic = Cache::remember('tagsWithHighTrafficSearchPage', 60 * 60 * 24 * 2, function () {
            return $this->tagRepository->getTagsWithHighTraffic();
        });

        $randomTags = Cache::remember('randomCategoriesSearchPage', 60 * 5, function () use ($tagsWithHighTraffic) {
            return $this->tagRepository->getRandomTags(exceptKeys: $tagsWithHighTraffic->pluck('key')->toArray());
        });

        return OfTagResource::collection($tagsWithHighTraffic->concat($randomTags));
    }

    public function getTagsIdsArray(): array
    {
        return Cache::rememberForever('tagsIds', function () {
            return OfTag::query()->pluck('id')->toArray();
        });
    }

    public function getNavbarDropdownsForMainPage(): array
    {
        return $this->getNavbarDropdowns();
    }

    public function getNavbarDropdownsForSearchPage(): array
    {
        return $this->getNavbarDropdowns('search');
    }

    private function getNavbarDropdowns($page = 'main'): array
    {
        $popularCategories = $page === 'main' ? $this->getCategoriesForMainPage() : $this->getCategoriesForSearchPage();
        $cache = Cache::remember('navbarDropdowns', 60 * 60 * 24 * 2, function () {

            return [
                'socialNetworkCategories' => $this->tagRepository->getSocialNetworkTags(),
                'countryCategories' =>  $this->tagRepository->getCountryTags(),
                'actionCategories' => $this->tagRepository->getActionTags()
            ];
        });
        return [
            new NavbarDropdownDTO('Popular', $popularCategories),
            new NavbarDropdownDTO('Country', OfTagResource::collection($cache['countryCategories'])),
            new NavbarDropdownDTO('Action', OfTagResource::collection($cache['actionCategories'])),
            new NavbarDropdownDTO('Social Network', OfTagResource::collection($cache['socialNetworkCategories'])),
        ];
    }

}
