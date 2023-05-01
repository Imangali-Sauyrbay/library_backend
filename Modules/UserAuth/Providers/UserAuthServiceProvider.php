<?php

namespace Modules\UserAuth\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use Modules\UserAuth\Entities\Ability;
use Modules\UserAuth\Entities\PersonalAccessToken;
use Modules\UserAuth\Entities\Role;
use Modules\UserAuth\Entities\User;

// use Illuminate\Database\Eloquent\Factory;

class UserAuthServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'UserAuth';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'userauth';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        Sanctum::authenticateAccessTokensUsing(function (PersonalAccessToken $token, $isValid) {
            if ($isValid) {
                return true;
            }
            return $token->can('remember') && $token->created_at->gt(now()->subYears(1));
        });

        $this->registerConfig();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        Relation::enforceMorphMap([
            User::getMorphName() => User::class,
            Role::getMorphName() => Role::class,
            Ability::getMorphName() => Ability::class,
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $path = module_path($this->moduleName, 'Config/config.php');

        $this->publishes([
            $path => config_path($this->moduleNameLower . '.php'),
        ], 'config');

        $this->mergeConfigFrom(
            $path,
            $this->moduleNameLower
        );
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
