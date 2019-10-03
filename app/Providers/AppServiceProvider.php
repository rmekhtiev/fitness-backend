<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Hall;
use App\Models\Issue;
use App\Models\Locker;
use App\Models\LockerClaim;
use App\Models\Pivot\ClientGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use App\Exceptions\ApiExceptionHandler;
use Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'clients' => Client::class,
            'halls' => Hall::class,
            'issues' => Issue::class,
            'employees' => Employee::class,
            'locker-claims' => LockerClaim::class,
            'lockers' => Locker::class,
            'users' => User::class,
            'client-group' => ClientGroup::class,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerExceptionHandler();
        $this->registerTelescope();
    }

    /**
     * Register the exception handler - extends the Dingo one
     *
     * @return void
     */
    protected function registerExceptionHandler()
    {
        $this->app->singleton('api.exception', function ($app) {
            return new ApiExceptionHandler($app['Illuminate\Contracts\Debug\ExceptionHandler'], Config('api.errorFormat'), Config('api.debug'));
        });
    }

    /**
     * Conditionally register the telescope service provider
     */
    protected function registerTelescope()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
