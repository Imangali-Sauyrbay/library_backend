<?php

namespace Modules\Library\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\Library;

// use Illuminate\Database\Eloquent\Factory;

class LibraryServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Library';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'library';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        Relation::enforceMorphMap([
            Library::getMorphName() => Library::class,
            Book::getMorphName() => Book::class,
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
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
