<?php

namespace App\Contracts;

use App\DTO\PaginatorDTO;
use App\Models\OfTag;
use App\Models\OfUser;
use Illuminate\Support\Collection;

interface OfUserRepositoryInterface
{
    public function searchOfUsers(string $text, $perPage = 50, $page = 1): PaginatorDTO;

    public function searchOfUserById(int $id);

    public function searchOfUsersByTag(OfTag $ofTag, $perPage = 50, $page = 1): PaginatorDTO;

    public function searchNewestOfUsers($limit = 50, $page = 1);

    public function searchTopOfUsers($limit = 50, $page = 1);

    public function searchFreeOfUsers($limit = 50, $page = 1): PaginatorDTO;

    public function searchOfUsersForAds($limit = 500): PaginatorDTO;

    public function searchFreeOfUsersOrderByLikes($limit = 50): PaginatorDTO;

    public function updateOfUsers(array $users);

    public function getTotalOfUsers();

    public function getTotalIndexedOfUsers();

    public function getOfUsersForIndexation(int $limit = 50): Collection;

    public function getSpecificOfUsersForIndexation(int $limit = 50): Collection;

    public function getOfUsersThatWereIndexedToday(): Collection;

    public function updateOfUsersIndexDate(array $usersIds, $index_date): int;

    public function createOfUsers(array $users);

    public function addRegularUsers(array $users): int;

    public function getOfUserByUsername($username): ?OfUser;

    public function getSimilarOfUsers(OfUser $ofUser): Collection;

    public function getCategoryOfUsers(OfTag $tag): Collection;

    public function getOfUsersWithTrialLinks(): Collection;
}