<?php

namespace Indianic\Textareafield;

use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            $this->app->booted(function () {
                $this->routes();
            });
            Nova::script('textareafield', __DIR__.'/../dist/js/field.js');
            Nova::style('textareafield', __DIR__.'/../dist/css/field.css');
        });
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config/' => config_path()], 'config');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->routes();
        $this->mergeConfigFrom(__DIR__.'/../config/openai.php', 'openai');
    }
    /**
     * [routes description]
     * @return [type] [description]
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }
        Route::prefix('nova-vendor/textareafield')
            ->group(__DIR__.'/../routes/api.php');
    }
}
