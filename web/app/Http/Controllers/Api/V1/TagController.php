<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\OfTagRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function getTagByKey(Request $request, OfTagRepositoryInterface $ofTagRepository)
    {
        $request->validate([
            'category' => 'required|string'
        ]);

        return $ofTagRepository->getTagByKey($request->get('category'));
    }

    public function getTagsWithHighTraffic(Request $request, OfTagRepositoryInterface $ofTagRepository)
    {
        $request->validate([
            'count' => 'nullable|integer'
        ]);
        return $ofTagRepository->getTagsWithHighTraffic($request->get('count', 30));
    }

    public function getAllTags(OfTagRepositoryInterface $ofTagRepository)
    {
        return $ofTagRepository->getAllTags();
    }

    public function getRandomTags(Request $request, OfTagRepositoryInterface $ofTagRepository)
    {
        $request->validate([
            'count' => 'nullable|integer',
            'except_keys' => 'nullable|array'
        ]);
        return $ofTagRepository->getRandomTags($request->get('count', 20), $request->get('except_keys', []));
    }

    public function getTotalOfTags(OfTagRepositoryInterface $ofTagRepository)
    {
        return $ofTagRepository->getTotalOfTags();
    }

    public function getTotalIndexedOfTags(OfTagRepositoryInterface $ofTagRepository)
    {
        return $ofTagRepository->getTotalIndexedOfTags();
    }

    public function getSocialNetworkTags(OfTagRepositoryInterface $ofTagRepository)
    {
        return $ofTagRepository->getSocialNetworkTags();
    }

    public function getCountryTags(OfTagRepositoryInterface $ofTagRepository)
    {
        return $ofTagRepository->getCountryTags();
    }

    public function getActionTags(OfTagRepositoryInterface $ofTagRepository)
    {
        return $ofTagRepository->getActionTags();
    }

    public function getPopularTags(OfTagRepositoryInterface $ofTagRepository)
    {
        return $ofTagRepository->getPopularTags();
    }

    public function getOfTagUsers(Request $request, OfTagRepositoryInterface $ofTagRepository)
    {
        $request->validate([
            'tag_key' => 'required|string'
        ]);

        return $ofTagRepository->getOfTagUsers($request->get('tag_key'));
    }
}
