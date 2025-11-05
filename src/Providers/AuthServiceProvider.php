<?php

namespace RbacAuth\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use RbacAuth\Console\RbacSeedCommand;
use RbacAuth\Http\Middleware\RoleMiddleware;
use RbacAuth\Models\Role;
use RbacAuth\Models\Permission;
use RbacAuth\Models\User;
use RbacAuth\Policies\RolePolicy;
use RbacAuth\Policies\PermissionPolicy;
use RbacAuth\Policies\UserPolicy;

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
    public function boot(Router $router): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RbacSeedCommand::class,
            ]);
        }
        $this->registerPolicies();

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $router->aliasMiddleware('role', RoleMiddleware::class);
    }
}
