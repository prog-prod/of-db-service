<?php

namespace App\Repositories;

use App\Contracts\ParserStatusRepositoryInterface;
use App\DTO\ParserInfoDTO;
use App\DTO\ParsersInfoDTO;
use App\Models\ParserCheckingRegularsStatus;
use App\Models\ParserStatus;
use App\Models\ParserUpdatingStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ParserStatusRepository implements ParserStatusRepositoryInterface
{

    public function getParserStatusesForThePeriod(Carbon $from, Carbon $to): Collection
    {
        $from = $from->startOfDay();
        $to = $to->endOfDay();
        return ParserStatus::
            query()
            ->selectRaw('
                    MAX(of_sign_version) as of_sign_version,
                    AVG(should_stop_parser) as should_stop_parser,
                    AVG(not_found_sequence) as not_found_sequence,
                    MAX(used_proxies_count) as used_proxies_count,
                    MAX(total_chunks_count) as total_chunks_count,
                    MAX(in_progress_chunks_count) as in_progress_chunks_count,
                    MAX(finished_chunks_count) as finished_chunks_count,
                    MAX(time_sec) as time_sec,
                    MAX(chunk_size) as chunk_size,
                    MAX(min_user_id_to_parse) as min_user_id_to_parse,
                    MAX(max_user_id_to_parse) as max_user_id_to_parse,
                    DATE(created_at) as date,
                    AVG(speed) as speed,
                    MAX(should_stop_parser) as should_stop_parser,
                    MAX(parsed_regulars) as parsed_regulars,
                    MAX(parsed_performers) as parsed_performers,
                    MAX(good_parsed) as good_parsed,
                    MAX(bad_parsed) as good_parsed,
                    MAX(total_parsed) as total_parsed,
                    MAX(total_progress_percent) as max_total_progress_percent,
                    AVG(average_response_time_sec) as average_response_time_sec
                ')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getActiveParsers(): ParsersInfoDTO
    {
        $time = Carbon::now()->subHour();
        $parsersStatuses = [
            'parser' => ParserStatus::query()->where('created_at', '>', $time)->latest()->first(),
            'parserUpdating' => ParserUpdatingStatus::query()->where('created_at', '>', $time)->latest()->first(),
            'parserCheckingRegulars' => ParserCheckingRegularsStatus::query()->where('created_at', '>', $time)->latest()->first(),
        ];

        $count = 0;

        foreach ($parsersStatuses as $key => $parserData) {
            if ($parserData) {
                $count += $parserData->status;
            }
        }

        return new ParsersInfoDTO(countActiveParsers: number_format($count), parsers: $parsersStatuses);
    }

    public function getParserUpdatingStatusesForThePeriod(Carbon $from, Carbon $to): Collection
    {
        $from = $from->startOfDay();
        $to = $to->endOfDay();
        return ParserUpdatingStatus::
        query()
            ->selectRaw('
                    MAX(of_sign_version) as of_sign_version,
                    MAX(total_count) as total_count,
                    MAX(good_count) as good_count,
                    MAX(bad_count) as bad_count,
                    MAX(prepared_performers) as prepared_performers,
                    MAX(updated_performers) as updated_performers,
                    MAX(used_proxies) as used_proxies,
                    MAX(time_sec) as time_sec,
                    AVG(speed) as speed,
                    DATE(created_at) as date
                ')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getParserCheckingRegularsStatusesForThePeriod(Carbon $from, Carbon $to): Collection
    {
        $from = $from->startOfDay();
        $to = $to->endOfDay();
        return ParserCheckingRegularsStatus::
        query()
            ->selectRaw('
                    MAX(of_sign_version) as of_sign_version,
                    MAX(total_count) as total_count,
                    MAX(good_count) as good_count,
                    MAX(bad_count) as bad_count,
                    MAX(regulars_need_to_be_checked_count) as regulars_need_to_be_checked_count,
                    MAX(regulars_became_model_count) as regulars_became_model_count,
                    MAX(used_proxies) as used_proxies,
                    MAX(total_progress_percent) as total_progress_percent,
                    MAX(time_sec) as time_sec,
                    AVG(speed) as speed,
                    DATE(created_at) as date
                ')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}
