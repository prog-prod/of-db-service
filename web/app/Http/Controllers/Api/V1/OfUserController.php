<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\OfTagRepositoryInterface;
use App\Contracts\OfUserRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OfUserController extends Controller
{

    public function searchTopOfUsers(Request $request, OfUserRepositoryInterface $ofUserRepository)
    {
        $request->validate([
            'page' => 'integer',
            'limit' => 'nullable|integer',
        ]);

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 50);
        return response()->json($ofUserRepository->searchTopOfUsers($limit, $page));
    }

    public function searchOfUsersForAds(Request $request, OfUserRepositoryInterface $ofUserRepository)
    {
        $request->validate([
            'limit' => 'nullable|integer',
        ]);

        $limit = $request->get('limit', 50);
        return response()->json($ofUserRepository->searchOfUsersForAds($limit));
    }

    public function searchFreeOfUsers(Request $request, OfUserRepositoryInterface $ofUserRepository)
    {
        $request->validate([
            'page' => 'integer',
            'limit' => 'nullable|integer',
        ]);

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 50);
        return response()->json($ofUserRepository->searchFreeOfUsers($limit, $page));
    }

    public function searchFreeOfUsersOrderByLikes(Request $request, OfUserRepositoryInterface $ofUserRepository)
    {
        $request->validate([
            'limit' => 'nullable|integer',
        ]);

        $limit = $request->get('limit', 50);
        return response()->json($ofUserRepository->searchFreeOfUsersOrderByLikes($limit));
    }

    public function getTotalOfUsers(Request $request, OfUserRepositoryInterface $ofUserRepository)
    {
        return response()->json($ofUserRepository->getTotalOfUsers());
    }

    public function searchNewestOfUsers(Request $request, OfUserRepositoryInterface $ofUserRepository)
    {
        $request->validate([
            'page' => 'integer',
            'limit' => 'nullable|integer',
        ]);

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 50);
        return response()->json($ofUserRepository->searchNewestOfUsers($limit, $page));
    }

    public function searchOfUsersByTag(Request $request, OfUserRepositoryInterface $ofUserRepository, OfTagRepositoryInterface $ofTagRepository)
    {
        $request->validate([
            'tag_key' => 'required|string',
            'page' => 'integer',
            'limit' => 'nullable|integer',
        ]);

        $tag = $ofTagRepository->getTagByKey($request->get('tag_key'));
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 50);
        return response()->json($ofUserRepository->searchOfUsersByTag($tag, $limit, $page));
    }

    public function searchOfUsers(Request $request, OfUserRepositoryInterface $ofUserRepository)
    {
        $request->validate([
            'text' => 'required|string',
            'page' => 'integer',
            'limit' => 'nullable|integer',
        ]);

        $text = $request->get('text');
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 50);
        return response()->json($ofUserRepository->searchOfUsers($text, $limit, $page));
    }

    public function getTotalIndexedOfUsers(Request $request, OfUserRepositoryInterface $ofUserRepository)
    {
        return response()->json($ofUserRepository->getTotalIndexedOfUsers());
    }

    public function getOfUserByUsername(Request $request, OfUserRepositoryInterface $ofUserRepository)
    {
        $request->validate([
            'username' => 'required|string',
        ]);

        $username = $request->get('username');
        return response()->json($ofUserRepository->getOfUserByUsername($username));
    }

    public function getOfUsersForIndexation(Request $request, OfUserRepositoryInterface $ofUserRepository)
    {
        return response()->json($ofUserRepository->getOfUsersForIndexation());
    }

    public function searchOfUserById(Request $request, OfUserRepositoryInterface $ofUserRepository)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $id = $request->get('id');
        return response()->json($ofUserRepository->searchOfUserById($id));
    }

    public function getSimilarOfUsers(Request $request, OfUserRepositoryInterface $ofUserRepository)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        return response()->json($ofUserRepository->getSimilarOfUsers());
    }

    public function getCategoryOfUsers(Request $request, OfTagRepositoryInterface $ofTagRepository, OfUserRepositoryInterface $ofUserRepository)
    {
        $request->validate([
            'tag_key' => 'required|string',
        ]);

        $tag = $request->get('tag_key');
        $tag = $ofTagRepository->getTagByKey($tag);
        return response()->json($ofUserRepository->getCategoryOfUsers($tag));
    }

    public function getMainLastModifiedDate(Request $request, OfUserRepositoryInterface $ofUserRepository)
    {
        return response()->json($ofUserRepository->getMainLastModifiedDate());
    }

    public function getRandomOfUserTag(Request $request, OfUserRepositoryInterface $ofUserRepository)
    {
        $request->validate([
            'of_user_id' => 'required|integer',
        ]);

        $ofUserId = $request->get('of_user_id');
        return response()->json($ofUserRepository->getRandomOfUserTag($ofUserId));
    }

    public function getTags(Request $request, OfUserRepositoryInterface $ofUserRepository)
    {
        $request->validate([
            'user_id' => 'required|integer',
        ]);

        $userId = $request->get('user_id');
        return response()->json($ofUserRepository->getOfUserTags($userId));
    }
}
