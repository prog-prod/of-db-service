<?php

namespace App\Http\Controllers\Api\Parser;

use App\Contracts\OfUserRepositoryInterface;
use App\Facades\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ParserRequest;
use App\Http\Requests\SearchOfUserRequest;
use App\Jobs\AddOfUsersJob;
use App\Jobs\AddRegularUsersJob;
use App\Jobs\UpdateOfUsersJob;

class ParserController extends Controller
{
    public function updateOfUsers(ParserRequest $request) {
        UpdateOfUsersJob::dispatch($request->get('usersArray'));
    }

    public function addOfUsers(ParserRequest $request) {
        AddOfUsersJob::dispatch($request->get('usersArray'));
    }
    public function addRegularOfUsers(ParserRequest $request) {
        AddRegularUsersJob::dispatch($request->get('usersArray'));
    }

    public function search(
        SearchOfUserRequest              $request,
        OfUserRepositoryInterface        $ofUserRepository,
    )
    {
        $searchText = Helper::escapeElasticSearchValue($request->get('q'));
        return $ofUserRepository->searchOfUsers($searchText)->data;
    }
}
