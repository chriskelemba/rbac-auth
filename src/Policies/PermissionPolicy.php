<?php

namespace RbacAuth\Policies;

use RbacAuth\Models\User;
use RbacAuth\Models\Permission;

class PermissionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view.permission');
    }

    public function view(User $user, Permission $permission): bool
    {
        return $user->hasPermission('view.permission');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('create.permission');
    }

    public function update(User $user, Permission $permission): bool
    {
        return $user->hasPermission('update.permission');
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $user->hasPermission('delete.permission');
    }
}
