<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\ChartDataServiceInterface;
use App\Contracts\OfTagRepositoryInterface;
use App\Contracts\OfUserRepositoryInterface;
use App\Contracts\ParserServiceInterface;
use App\Contracts\ParserStatusRepositoryInterface;
use App\Contracts\UserRepositoryInterface;
use App\Http\Controllers\BaseController;
use App\Http\Requests\DashboardRequest;
use App\Http\Resources\ParserCheckingRegularsStatusResource;
use App\Http\Resources\ParserStatusResource;
use App\Http\Resources\ParserUpdatingStatusResource;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class DashboardController extends BaseController
{
    public function index(
        DashboardRequest          $request,
        OfUserRepositoryInterface $ofUserRepository,
        OfTagRepositoryInterface  $ofTagRepository,
        UserRepositoryInterface   $userRepository,
        ParserServiceInterface    $parserService,
        ChartDataServiceInterface $chartService,
    )
    {
        $ofTagsNumber = number_format($ofTagRepository->getTotalOfTags());
        $totalIndexedOfUsers = number_format($ofUserRepository->getTotalIndexedOfUsers());
        $totalIndexedOfTags = number_format($ofTagRepository->getTotalIndexedOfTags());
        $usersNumber = number_format($userRepository->getTotalCountUsers());
        $start = $request->has('start') ? Carbon::parse($request->get('start')) : $parserService->getDefaultStartDate();
        $end = $request->has('end') ? Carbon::parse($request->get('end')) : $parserService->getDefaultEndDate();
        $parserData = $parserService->getParserStatusesForThePeriod($start, $end);
        $parserUpdatedData = $parserService->getParserUpdatingStatusesForThePeriod($start, $end);
        $parserCheckingRegularsData = $parserService->getParserCheckingRegularsStatusesForThePeriod($start, $end);
        $requestsPerSecondsData = $chartService->getRequestsPerSecondForThePeriod($start, $end);

        return $this->showView('Dashboard', [
            'usersNumber' => $usersNumber,
            'ofTagsNumber' => $ofTagsNumber,
            'indexedOfTags' => $totalIndexedOfTags,
            'indexedOfUsers' => $totalIndexedOfUsers,
            'startDate' => $start->format('Y-m-d'),
            'endDate' => $end->format('Y-m-d'),
            'requestsPerSecondsData' => $requestsPerSecondsData,
            'parserCheckingRegularsData' => ParserCheckingRegularsStatusResource::collection($parserCheckingRegularsData),
            'parserDataUpdated' => ParserUpdatingStatusResource::collection($parserUpdatedData),
            'parserData' => ParserStatusResource::collection($parserData),
            'meta' => [
                'pageTitle' => 'Dashboard',
                'breadcrumbs' => []
            ]
        ]);
    }

    public function getTotalOfUsers(OfUserRepositoryInterface $ofUserRepository)
    {
        return Cache::remember('total-of-users-count', 1200, function () use ($ofUserRepository) {
            return number_format($ofUserRepository->getTotalOfUsers());
        });
    }

    public function getActiveParsers(ParserStatusRepositoryInterface $parserStatusRepository): JsonResponse
    {
        return response()->json($parserStatusRepository->getActiveParsers());
    }
}
