<?php

namespace App\Repositories;

use App\Contracts\OfTagsGroupRepositoryInterface;
use App\Models\OfTagsGroup;
use Illuminate\Database\Eloquent\Collection;

class OfTagsGroupRepository implements OfTagsGroupRepositoryInterface
{

    public function getOfTagsGroups(): Collection
    {
        return OfTagsGroup::with('tags')->get();
    }

    public function getOfTagsGroupsWithLimitedTags($limit = 10): Collection
    {
        return OfTagsGroup::withLimitedTags($limit);
    }

    public function getOfTagsLocationsGroupsWithLimitedTags(int $maxTagsLength = 10)
    {
        return OfTagsGroup::withLimitedTags($maxTagsLength, true);
    }
}
