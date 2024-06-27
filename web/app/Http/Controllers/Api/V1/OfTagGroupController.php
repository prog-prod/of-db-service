<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\OfTagsGroupRepository;
use Illuminate\Http\Request;

class OfTagGroupController extends Controller
{

    public function getOfTagsGroupsWithLimitedTags(Request $request, OfTagsGroupRepository $ofTagGroupRepository)
    {
        $request->validate([
            'limit' => 'nullable|integer'
        ]);
        return $ofTagGroupRepository->getOfTagsGroupsWithLimitedTags($request->get('limit', 30));
    }

    public function getOfTagsLocationsGroupsWithLimitedTags(Request $request, OfTagsGroupRepository $ofTagGroupRepository)
    {
        $request->validate([
            'limit' => 'nullable|integer'
        ]);
        return $ofTagGroupRepository->getOfTagsLocationsGroupsWithLimitedTags($request->get('limit', 30));
    }

    public function getOfTagsGroupByKeyWithTags(Request $request, OfTagsGroupRepository $ofTagGroupRepository)
    {
        $request->validate([
            'key' => 'required|string'
        ]);
        return $ofTagGroupRepository->getOfTagsGroupByKeyWithTags($request->get('key'));
    }
}
