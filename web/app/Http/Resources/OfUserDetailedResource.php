<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfUserDetailedResource extends JsonResource
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
            "social_links" => $this->getSocialLinks(),
            "is_estimated_sub_count" => $this->is_estimated_sub_count,
            "estimated_income" => $this->estimated_income,
            "estimated_subscribers_count" => $this->estimated_subscribers_count,
            "username" => $this->username,
            "avatar" => $this->avatar_computed,
            "avatar_thumbs" => $this->avatar_thumbs,
            "free_trial_link" => $this->free_trial_link,
            "favorites_count" => $this->favorites_count,
            "is_verified" => $this->is_verified,
            "join_date" => $this->join_date,
            "location" => $this->location,
            "photos_count" => $this->photos_count,
            "posts_count" => $this->posts_count,
            "subscribe_price" => $this->subscribe_price,
            "videos_count" => $this->videos_count,
            "website" => $this->website_computed,
            "date_published" => $this->date_published,
            "subscribers_count" => $this->subscribers_count,
            "category" => new OfTagResource($this->category),
            "tags" => OfTagResource::collection($this->whenLoaded('tags', $this->tags)),
        ];
    }
}
