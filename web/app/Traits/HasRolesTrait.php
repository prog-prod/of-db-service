<?php

namespace App\Traits;

use Spatie\Permission\Traits\HasRoles;

trait HasRolesTrait
{
    use HasRoles;

    public function permissionNamesViaRoles(): array
    {
        return collect($this->getPermissionsViaRoles())->map(function ($permission) {
           return $permission->name;
        })->toArray();
    }
}