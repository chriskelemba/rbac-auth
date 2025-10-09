<?php

namespace RbacAuth\Http\Controllers;

use RbacAuth\Models\Role;
use RbacAuth\Http\Resources\RolePermissionResource;

class RoleController extends Controller
{
    public function getPermissionForRole(Role $role)
    {
        $role->load('permissions');
        return sendApiResponse(
            ['role' => new RolePermissionResource($role)],
            'Permissions for role fetched successfully',
            200
        );
    }

    public function assignPermission(Role $role, $permissionId)
    {
        $role->permissions()->syncWithoutDetaching([
            $permissionId => [
                'added_on' => now()->toDateString(),
            ],
        ]);
        $syncedPermissions = $role->load('permissions');
        return sendApiResponse(['role' => new RolePermissionResource($syncedPermissions)], 'Permissions synced successfully', 200);
    }

    public function revokePermission(Role $role, $permissionId)
    {
        $role->permissions()->detach($permissionId);
        $syncedPermissions = $role->load('permissions');
        return sendApiResponse(['role' => new RolePermissionResource($syncedPermissions)], 'Permissions revoked successfully', 200);
    }
}
