<?php

namespace App\Contracts;

interface OfTagsGroupRepositoryInterface
{
    public function getOfTagsGroups();

    public function getOfTagsGroupsWithLimitedTags($limit = 10);

    public function getOfTagsLocationsGroupsWithLimitedTags(int $maxTagsLength = 10);
}
