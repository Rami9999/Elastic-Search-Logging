<?php

namespace Zeour\ElasticSearchLogging;

use Illuminate\Support\ServiceProvider;

class ElasticSearchLoggingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Zeour\ElasticSearchLogging\ElasticSearchLogger');
        $this->app->make('Zeour\ElasticSearchLogging\ElasticSearchFallbackHandler');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/routes.php';
    }
}
