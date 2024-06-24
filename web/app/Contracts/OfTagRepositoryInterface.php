<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface OfTagRepositoryInterface
{
    public function getTagsWithHighTraffic(int $count = 30);

    public function getRandomTags(int $count = 20, array $exceptKeys = []);

    public function getTotalOfTags();

    public function getAllTags(): Collection;

    public function getTotalIndexedOfTags();

    public function getSocialNetworkTags();

    public function getCountryTags();

    public function getActionTags();

    public function getPopularTags();
    public function getTagByKey(string $category);
}
