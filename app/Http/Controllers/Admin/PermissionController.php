<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\PermissionResource;
use App\Models\Permission;

class PermissionController
{
    public function index()
    {
        return PermissionResource::collection(Permission::all());
    }
}
