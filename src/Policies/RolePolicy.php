<?php

namespace RbacAuth\Policies;

use RbacAuth\Models\User;
use RbacAuth\Models\Role;

class RolePolicy
{
    /**
     * Determine whether the user can view any roles.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view.role');
    }

    /**
     * Determine whether the user can view a specific role.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasPermission('view.role');
    }

    /**
     * Determine whether the user can create roles.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create.role');
    }

    /**
     * Determine whether the user can update a specific role.
     */
    public function update(User $user, Role $role): bool
    {
        return $user->hasPermission('update.role');
    }

    /**
     * Determine whether the user can delete a specific role.
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermission('delete.role');
    }
}
