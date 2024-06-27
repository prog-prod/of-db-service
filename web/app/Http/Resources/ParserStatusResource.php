<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParserStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'of_sign_version' => $this->of_sign_version,
            'total_parsed' => $this->total_parsed,
            'good_parsed' => $this->good_parsed,
            'bad_parsed' => $this->bad_parsed,
            'prepared_performers' => $this->prepared_performers,
            'parsed_performers' => $this->parsed_performers,
            'parsed_regulars' => $this->parsed_regulars,
            'prepared_regulars' => $this->prepared_regulars,
            'should_stop_parser' => $this->should_stop_parser,
            'not_found_sequence' => (float)$this->not_found_sequence,
            'max_user_id_to_parse' => $this->max_user_id_to_parse,
            'min_user_id_to_parse' => $this->min_user_id_to_parse,
            'chunk_size' => $this->chunk_size,
            'speed' => (float)$this->speed,
            'average_response_time_sec' => $this->average_response_time_sec,
            'total_progress_percent' => $this->total_progress_percent,
            'time_sec' => $this->time_sec,
            'finished_chunks_count' => $this->finished_chunks_count,
            'in_progress_chunks_count' => $this->in_progress_chunks_count,
            'total_chunks_count' => $this->total_chunks_count,
            'used_proxies_count' => $this->used_proxies_count,
            'used_proxies_list' => $this->used_proxies_list,
            'created_at' => $this->created_at,
            'date' => $this->date,
        ];
    }
}
