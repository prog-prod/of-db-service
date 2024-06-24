<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\OfTagRepository;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function getTagByKey(Request $request, OfTagRepository $ofTagRepository)
    {
        $request->validate([
            'category' => 'required|string'
        ]);

        return $ofTagRepository->getTagByKey($request->get('category'));
    }

    public function getTagsWithHighTraffic(Request $request, OfTagRepository $ofTagRepository)
    {
        $request->validate([
            'count' => 'nullable|integer'
        ]);
        return $ofTagRepository->getTagsWithHighTraffic($request->get('count', 30));
    }

    public function getAllTags(OfTagRepository $ofTagRepository)
    {
        return $ofTagRepository->getAllTags();
    }

    public function getRandomTags(Request $request, OfTagRepository $ofTagRepository)
    {
        $request->validate([
            'count' => 'nullable|integer',
            'except_keys' => 'nullable|array'
        ]);
        return $ofTagRepository->getRandomTags($request->get('count', 20), $request->get('except_keys', []));
    }

    public function getTotalOfTags(OfTagRepository $ofTagRepository)
    {
        return $ofTagRepository->getTotalOfTags();
    }

    public function getTotalIndexedOfTags(OfTagRepository $ofTagRepository)
    {
        return $ofTagRepository->getTotalIndexedOfTags();
    }

    public function getSocialNetworkTags(OfTagRepository $ofTagRepository)
    {
        return $ofTagRepository->getSocialNetworkTags();
    }

    public function getCountryTags(OfTagRepository $ofTagRepository)
    {
        return $ofTagRepository->getCountryTags();
    }

    public function getActionTags(OfTagRepository $ofTagRepository)
    {
        return $ofTagRepository->getActionTags();
    }

    public function getPopularTags(OfTagRepository $ofTagRepository)
    {
        return $ofTagRepository->getPopularTags();
    }
}
