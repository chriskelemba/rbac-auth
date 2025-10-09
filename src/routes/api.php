<?php

use Illuminate\Support\Facades\Route;
use RbacAuth\Http\Controllers\AuthController;
use RbacAuth\Http\Controllers\RoleController;
use RbacAuth\Http\Controllers\UserController;
use RbacAuth\Http\Controllers\PermissionController;

Route::prefix('api')->group(function () {
    // Authentication
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth:sanctum'])->group(function () {
        // User Management
        Route::apiResource('users', UserController::class);
        Route::prefix('users/{user}')->group(function () {
            Route::get('roles', [UserController::class, 'fetchUserRoles']);
            Route::post('roles/{role}', [UserController::class, 'assignRole']);
            Route::delete('roles/{role}', [UserController::class, 'revokeRole']);
        });

        // Roles
        Route::apiResource('roles', RoleController::class);
        Route::prefix('roles/{role}')->group(function () {
            Route::get('permissions', [RoleController::class, 'getPermissionForRole']);
            Route::post('permissions/{permission}', [RoleController::class, 'assignPermission']);
            Route::delete('permissions/{permission}', [RoleController::class, 'revokePermission']);
        });

        // Permissions
        Route::apiResource('permissions', PermissionController::class);
    });
});
