<?php

namespace RbacAuth\Http\Controllers;

use RbacAuth\Models\Role;
use RbacAuth\Http\Resources\RolePermissionResource;

class RoleController extends Controller
{
    public function getPermissionForRole($roleId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);

        return sendApiResponse(
            ['role' => new RolePermissionResource($role)],
            'Permissions for role fetched successfully',
            200
        );
    }

    public function assignPermission($roleId, $permissionId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);

        $role->permissions()->syncWithoutDetaching([
            $permissionId => ['added_on' => now()->toDateString()],
        ]);

        $role->load('permissions');

        return sendApiResponse(
            ['role' => new RolePermissionResource($role)],
            'Permission assigned successfully',
            200
        );
    }

    public function revokePermission($roleId, $permissionId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);

        $role->permissions()->detach($permissionId);

        $role->load('permissions');

        return sendApiResponse(
            ['role' => new RolePermissionResource($role)],
            'Permission revoked successfully',
            200
        );
    }
}