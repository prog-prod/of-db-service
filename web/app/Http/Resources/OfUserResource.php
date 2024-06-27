<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OfUserResource extends JsonResource
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
            "id" => $this->id,
            "name" => $this->name,
            "avatar" => $this->avatar,
            "avatar_thumbs" => $this->avatar_thumbs,
            "username" => $this->username,
            "favorites_count" => $this->favorites_count,
            "photos_count" => $this->photos_count,
            "videos_count" => $this->videos_count,
            "is_verified" => $this->is_verified,
            "free_trial_link" => $this->free_trial_link,
            "subscribe_price" => $this->subscribe_price,
            "about" => $this->short_about,
            "website" => $this->website_computed,
            "date_published" => $this->date_published->format('Y-m-d'),
            "updated_at" => $this->updated_at?->format('Y-m-d'),
        ];
    }
}
