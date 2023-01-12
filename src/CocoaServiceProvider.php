<?php

namespace ivampiresp\Cocoa;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ivampiresp\Cocoa\Commands\ChangePassword;
use ivampiresp\Cocoa\Commands\CreateAdmin;

class CocoaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/cocoa.php',
            'cocoa'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

            $this->commands([
                ChangePassword::class,
                CreateAdmin::class,
            ]);
        }

        Http::macro('remote', function () {
            return Http::withoutVerifying()->withToken(config('cocoa.api_token'))->baseUrl(config('cocoa.url'))->acceptJson();
        });

        $this->publishes([
            // __DIR__ . '/views' => base_path('resources/views/vendor/cocoa'),
            __DIR__ . '/../config/cocoa.php' => $this->app->configPath('cocoa.php'),
        ]);

        $this->loadViewsFrom(__DIR__ . '/views', 'Cocoa');



        Route::middleware(['remote'])
            ->prefix('remote')
            ->as('remote.')
            ->group(__DIR__ . '/../routes/remote.php');

        Route::middleware(['web'])
            ->group(__DIR__ . '/../routes/web.php');



    }
}
