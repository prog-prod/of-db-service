<?php

namespace App\Services;

use App\Contracts\CategoryServiceInterface;
use App\DTO\BreadcrumbDTO;
use App\Models\OfTagsGroup;

class CategoryService implements CategoryServiceInterface
{

    public function getGroupsArray(): array
    {
        return [
            ['name' => 'Action', 'key' => 'action', 'title' => 'Top Action Profiles', 'description' => 'Top Action Profiles', 'order' => 1],
            ['name' => 'Age', 'key' => 'age', 'title' => 'Top Age Profiles', 'description' => 'Top Age Profiles', 'order' => 2],
            ['name' => 'Gender', 'key' => 'gender', 'title' => 'Top Gender Profiles', 'description' => 'Top Gender Profiles', 'order' => 3],
            ['name' => 'Orientation', 'key' => 'orientation', 'title' => 'Top Orientation Profiles', 'description' => 'Top Orientation Profiles', 'order' => 4],
            ['name' => 'Body Attributes', 'key' => 'body_attributes', 'title' => 'Top Body Attributes Profiles', 'description' => 'Top Body Attributes Profiles', 'order' => 5],
            ['name' => 'Body Type', 'key' => 'body_type', 'title' => 'Top Body Type Profiles', 'description' => 'Top Body Type Profiles', 'order' => 6],
            ['name' => 'Ethnicity', 'key' => 'ethnicity', 'title' => 'Top Ethnicity Profiles', 'description' => 'Top Ethnicity Profiles', 'order' => 7],
            ['name' => 'Hair', 'key' => 'hair', 'title' => 'Top Hair Profiles', 'description' => 'Top Hair Profiles', 'order' => 8],
            ['name' => 'Performer', 'key' => 'performer', 'title' => 'Top Performer Profiles', 'description' => 'Top Performer Profiles', 'order' => 9],
            ['name' => 'Scenario', 'key' => 'scenario', 'title' => 'Top Scenario Profiles', 'description' => 'Top Scenario Profiles', 'order' => 10],
            ['name' => 'Top', 'key' => 'top', 'title' => 'Top', 'description' => 'Top', 'order' => 11],
            ['name' => 'Locations', 'key' => 'locations', 'title' => 'Other', 'description' => 'Other', 'order' => 12],
            ['name' => 'Country', 'key' => 'country', 'title' => 'Top Country Profiles Profiles', 'description' => 'Top Country Profiles', 'order' => 13],
            ['name' => 'Region', 'key' => 'region', 'title' => 'Top Region Profiles', 'description' => 'Top Region Profiles', 'order' => 14],
            ['name' => 'State', 'key' => 'state', 'title' => 'Top State Profiles', 'description' => 'Top State Profiles', 'order' => 15],
            ['name' => 'City', 'key' => 'city', 'title' => 'Top City Profiles', 'description' => 'Top City Profiles', 'order' => 16],
            ['name' => 'Social network', 'key' => 'social_network', 'title' => 'Top Social network Profiles', 'description' => 'Top Social network Profiles', 'order' => 17],
            ['name' => 'Other', 'key' => 'other', 'title' => 'Top Other Profiles', 'description' => 'Top Other Profiles', 'order' => 18],
            ['name' => 'Most Populated', 'key' => 'most_populated', 'title' => 'Top Most Populated Profiles', 'description' => 'Top Most Populated Profiles', 'order' => 19],
        ];
    }

    public function getBreadcrumbsForOfTagsGroup(OfTagsGroup $ofTagsGroup): array
    {
        $isLocation = $ofTagsGroup->isLocation();

        $breadcrumbs = $isLocation ? [
            new BreadcrumbDTO(name: 'Locations', url: route('categories.locations')),
        ] : [
            new BreadcrumbDTO(name: 'Categories', url: route('categories.index')),
        ];

        $breadcrumbs[] = new BreadcrumbDTO(name: $ofTagsGroup->name, url: route('categories.group', $ofTagsGroup));

        return $breadcrumbs;
    }
}
