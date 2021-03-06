<?php

namespace Dewsign\NovaSpaces\Providers;

use Laravel\Nova\Nova;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Dewsign\NovaSpaces\Models\CustomItem;
use Illuminate\Database\Eloquent\Relations\Relation;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->publishConfigs();
        $this->bootViews();
        $this->bootAssets();
        $this->bootCommands();
        $this->publishDatabaseFiles();
        $this->registerWebRoutes();
        $this->registerMorphMaps();
        $this->configurePagination();
        $this->loadTranslations();
        $this->registerBladeExtensions();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Nova::resources([
        //     SpaceItem::class,
        // ]);

        $this->mergeConfigFrom(
            $this->getConfigsPath(),
            'novaspaces'
        );
    }

    /**
     * Publish configuration file.
     *
     * @return void
     */
    private function publishConfigs()
    {
        $this->publishes([
            $this->getConfigsPath() => config_path('novaspaces.php'),
        ], 'config');
    }

    /**
     * Get local package configuration path.
     *
     * @return string
     */
    private function getConfigsPath()
    {
        return __DIR__.'/../Config/novaspaces.php';
    }

    /**
     * Register the artisan packages' terminal commands
     *
     * @return void
     */
    private function bootCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                // MyCommand::class,
            ]);
        }
    }

    /**
     * Load custom views
     *
     * @return void
     */
    private function bootViews()
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'nova-spaces');
        $this->publishes([
            __DIR__.'/../Resources/views' => resource_path('views/vendor/nova-spaces'),
        ]);
    }

    /**
     * Define publishable assets
     *
     * @return void
     */
    private function bootAssets()
    {
        $this->publishes([
            __DIR__.'/../Resources/assets/js' => resource_path('assets/js/vendor/nova-spaces'),
        ], 'js');
    }

    private function publishDatabaseFiles()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');

        $this->loadFactories();

        $this->publishes([
            __DIR__ . '/../Database/factories' => base_path('database/factories')
        ], 'factories');

        $this->publishes([
            __DIR__ . '/../Database/migrations' => base_path('database/migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/../Database/seeds' => base_path('database/seeds')
        ], 'seeds');
    }

    /**
     * Only load the factories in non-production ready environments
     *
     * @return void
     */
    public function loadFactories()
    {
        if (App::environment(['production', 'staging'])) {
            return;
        }

        $this->app->make('Illuminate\Database\Eloquent\Factory')->load(
            __DIR__ . '/../Database/factories'
        );
    }

    /**
     * Load Web Routes into the application
     *
     * @return void
     */
    private function registerWebRoutes()
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    /**
     * Register the Mophmaps
     *
     * @return void
     */
    private function registerMorphmaps()
    {
        Relation::morphMap([
            'novaspaces.custom' => CustomItem::class,
        ]);
    }

    /**
     * Set te default pagination to not use bootstrap markup
     *
     * @return void
     */
    private function configurePagination()
    {
        Paginator::defaultView('pagination::default');
    }

    private function loadTranslations()
    {
        $this->loadJSONTranslationsFrom(__DIR__.'/../Resources/lang', 'novaspaces');
    }

    public function registerBladeExtensions()
    {
        Blade::directive('spaces', function ($expression = []) {
            return "<?php echo \Dewsign\NovaSpaces\Support\RenderEngine::renderSpaces({$expression}); ?>";
        });
    }
}
