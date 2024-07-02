<?php

namespace App\Services;

use App\Contracts\ParserServiceInterface;
use App\Contracts\ParserStatusRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class ParserService implements ParserServiceInterface
{
    private ParserStatusRepositoryInterface $repository;

    public function __construct()
    {
        $this->repository = app(ParserStatusRepositoryInterface::class);
    }

    public function getParserStatusesForThePeriod(Carbon $from, Carbon $to)
    {
        return $this->repository->getParserStatusesForThePeriod($from, $to);
    }

    public function getDefaultStartDate(): Carbon
    {
        return Carbon::now()->subWeek();
    }

    public function getDefaultEndDate(): Carbon
    {
        return Carbon::now();
    }

    public function startParsingModels(): Response
    {
        return Http::timeout(10)->get(config('app.parser-url').'/parse');
    }
    public function stopParsingModels(): Response
    {
        return Http::timeout(10)->get(config('app.parser-url').'/stop');
    }
    public function startUpdatingModels(): Response
    {
        return Http::timeout(10)->get(config('app.parser-url').'/start-updating');
    }
    public function stopUpdatingModels(): Response
    {
        return Http::timeout(10)->get(config('app.parser-url').'/stop-updating');
    }
    public function startCheckingRegulars(): Response
    {
        return Http::timeout(10)->get(config('app.parser-url').'/start-checking-regulars');
    }
    public function stopCheckingRegulars(): Response
    {
        return Http::timeout(10)->get(config('app.parser-url').'/stop-checking-regulars');
    }
    public function syncParserStatuses(): JsonResponse
    {
        Http::timeout(10)->get(config('app.parser-url').'/check');
        Http::timeout(10)->get(config('app.parser-url').'/check-updating');
        Http::timeout(10)->get(config('app.parser-url').'/check-checking-regulars');

        return response()->json('ok');
    }

    public function getParserUpdatingStatusesForThePeriod(Carbon $from, Carbon $to)
    {
        return $this->repository->getParserUpdatingStatusesForThePeriod($from, $to);
    }

    public function getParserCheckingRegularsStatusesForThePeriod(Carbon $from, Carbon $to)
    {
        return $this->repository->getParserCheckingRegularsStatusesForThePeriod($from, $to);
    }
}
