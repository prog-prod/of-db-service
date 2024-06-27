<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfTagsGroupResource extends JsonResource
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
            'name' => $this->name,
            'key' => $this->key,
            'title' => $this->title,
            'description' => $this->description,
            'order' => $this->order,
            'tags' => OfTagResource::collection($this->tags),
            'tags_count' => $this->tags_count,
        ];
    }
}
