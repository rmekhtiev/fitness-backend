<?php

namespace App\Providers;

use App\Notifications\Channels\SigmaSmsChannel;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\ServiceProvider;
use Notification;

use App\SigmaSms\Client as SigmaSmsClient;
use App\SigmaSms\Credentials as SigmaSmsCredentials;

class SigmaSmsChannelServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('sigmasms', function ($app) {
                return new SigmaSmsChannel(
                    new SigmaSmsClient(new SigmaSmsCredentials(
                        $this->app['config']['services.sigmasms.username'],
                        $this->app['config']['services.sigmasms.password'],
                        $this->app['config']['services.sigmasms.jwt_filename']
                    )),
                    $this->app['config']['services.sigmasms.sms_from']
                );
            });
        });
    }
}
