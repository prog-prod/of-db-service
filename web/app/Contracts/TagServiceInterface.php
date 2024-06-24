<?php

namespace App\Contracts;

interface TagServiceInterface
{
    public function getCategoriesForMainPage();

    public function getNavbarDropdownsForMainPage();

    public function getNavbarDropdownsForSearchPage();

    public function getCategoriesForSearchPage();

    public function getTagsIdsArray(): array;
}
