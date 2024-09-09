<?php

namespace Modules\Kepegawaian\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class KepegawaianServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('Kepegawaian', 'Database/Migrations'));
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
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('Kepegawaian', 'Config/config.php') => config_path('kepegawaian.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Kepegawaian', 'Config/config.php'), 'kepegawaian'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/kepegawaian');

        $sourcePath = module_path('Kepegawaian', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/kepegawaian';
        }, \Config::get('view.paths')), [$sourcePath]), 'kepegawaian');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/kepegawaian');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'kepegawaian');
        } else {
            $this->loadTranslationsFrom(module_path('Kepegawaian', 'Resources/lang'), 'kepegawaian');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path('Kepegawaian', 'Database/factories'));
        }
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
}
