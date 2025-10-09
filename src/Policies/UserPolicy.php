<?php

namespace RbacAuth\Policies;

use RbacAuth\Models\User;

class UserPolicy
{
    /**
     * Determine whether the authenticated user can view the list of users.
     */
    public function viewAny(User $authUser): bool
    {
        return $authUser->hasPermissionTo('view.user');
    }

    /**
     * Determine whether the authenticated user can view a specific user.
     */
    public function view(User $authUser, User $user): bool
    {
        return $authUser->hasPermissionTo('view.user');
    }

    /**
     * Determine whether the authenticated user can create a new user.
     */
    public function create(User $authUser): bool
    {
        return $authUser->hasPermissionTo('create.user');
    }

    /**
     * Determine whether the authenticated user can update a user.
     */
    public function update(User $authUser, User $user): bool
    {
        return $authUser->hasPermissionTo('update.user');
    }

    /**
     * Determine whether the authenticated user can delete a user.
     */
    public function delete(User $authUser, User $user): bool
    {
        // Prevent self-deletion
        if ($authUser->id === $user->id) {
            return false;
        }

        return $authUser->hasPermissionTo('delete.user');
    }

    /**
     * Determine whether the authenticated user can assign roles to a user.
     */
    public function assignRole(User $authUser, User $user): bool
    {
        // Cannot assign role to self
        if ($authUser->id === $user->id) {
            return false;
        }

        return $authUser->hasPermissionTo('manage.user');
    }

    /**
     * Determine whether the authenticated user can revoke roles from a user.
     */
    public function revokeRole(User $authUser, User $user): bool
    {
        // Cannot revoke own roles
        if ($authUser->id === $user->id) {
            return false;
        }

        return $authUser->hasPermissionTo('manage.user');
    }
}