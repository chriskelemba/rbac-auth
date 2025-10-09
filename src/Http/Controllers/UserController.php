<?php

namespace RbacAuth\Http\Controllers;

use RbacAuth\Http\Resources\RoleResource;
use RbacAuth\Http\Resources\UserResource;
use RbacAuth\Models\User;

class UserController extends Controller
{    
    public function fetchUserRoles(User $user)
    {
        $this->authorize('view', $user);

        return sendApiResponse(
            ['roles' => RoleResource::collection($user->roles)],
            'User roles fetched successfully',
            200
        );
    }

    public function assignRole(User $user, $roleId)
    {
        $this->authorize('assignRole', $user);

        $user->roles()->syncWithoutDetaching([
            $roleId => [
                'start_date' => now()->toDateString(),
            ],
        ]);
        $user->load('roles.permissions');

        return sendApiResponse(
            ['user' => new UserResource($user)],
            'Role assigned to user successfully',
            200
        );
    }

    public function revokeRole(User $user, $roleId)
    {
        $this->authorize('revokeRole', $user);

        $user->roles()->detach($roleId);
        $user->load('roles.permissions');

        return sendApiResponse(
            ['user' => new UserResource($user)],
            'Role revoked from user successfully',
            200
        );
    }
}