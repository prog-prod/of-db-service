<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParserUpdatingStatusResource extends JsonResource
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
            'status' => $this->status,
            'of_sign_version' => $this->of_sign_version,
            'total_count' => $this->total_count,
            'good_count' => $this->good_count,
            'bad_count' => $this->bad_count,
            'prepared_performers' => $this->prepared_performers,
            'updated_performers' => $this->updated_performers,
            'used_proxies' => $this->used_proxies,
            'speed' => (float)$this->speed,
            'time_sec' => $this->time_sec,
            'created_at' => $this->created_at,
            'date' => $this->date,
        ];
    }
}
