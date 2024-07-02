<?php

namespace App\Contracts;

use App\Models\OfTagsGroup;

interface CategoryServiceInterface
{

    public function getGroupsArray();

    public function getBreadcrumbsForOfTagsGroup(OfTagsGroup $ofTagsGroup);
}
