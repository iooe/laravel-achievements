<?php

namespace tizis\achievements\Providers;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use tizis\laraComments\Contracts\ICommentable;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        $this->app->bind(
            ICommentable::class
        );

        $this->publishes([
            __DIR__ . '/../../config/achievements.php' => config_path('achievements.php'),
        ], 'config');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/achievements.php',
            'achievements'
        );
    }
}