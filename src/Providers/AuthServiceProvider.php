<?php

namespace RbacAuth\Providers;

use RbacAuth\Console\RbacSeedCommand;
use Illuminate\Support\Facades\Route;
use RbacAuth\Models\Role;
use RbacAuth\Models\Permission;
use RbacAuth\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the package.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Permission::class => PermissionPolicy::class,
        Role::class => RolePolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RbacSeedCommand::class,
            ]);
        }
        $this->registerPolicies();

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        Route::model('role', Role::class);
        Route::model('permission', Permission::class);
        Route::model('user', User::class);

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}
