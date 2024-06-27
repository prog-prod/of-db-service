<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
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
          'name' => $this->name,
          'email' => $this->email,
          'is_confirmed' => $this->is_confirmed,
            'roles' => RoleResource::collection($this->roles),
            'permissions' => PermissionResource::collection($this->getAllPermissions()),
            'isSuperAdmin' => $this->isSuperAdmin()
        ];
    }
}
