<?php


namespace App\Providers;


use App\Serializers\ReststateSerializer;
use Dingo\Api\Facade\API;
use Dingo\Api\Facade\Route;
use Dingo\Api\Transformer\Adapter\Fractal;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Specialtactics\L5Api\L5ApiServiceProvider as PackageServiceProvider;
use League\Fractal\Manager;
use Specialtactics\L5Api\Console\Commands\MakeApiResource;

class L5ApiServiceProvider extends PackageServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        // Set API Transformer Adapter & properties
        $this->app['Dingo\Api\Transformer\Factory']->setAdapter(function ($app) {
            return new Fractal((new Manager)->setSerializer(new ReststateSerializer()), 'include', ',');
        });

        // Register Fascades
        $loader = AliasLoader::getInstance();
        $loader->alias('API', API::class);
        $loader->alias('APIRoute', Route::class);

        if ($this->app->runningInConsole()) {
            $this->commands(MakeApiResource::class);
        }
    }

    /**
     * Bootstrap the application services.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function boot(Router $router, Dispatcher $event)
    {
    }
}
