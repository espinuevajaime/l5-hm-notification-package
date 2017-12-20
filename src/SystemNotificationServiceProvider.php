<?php

namespace Handymaster\SystemNotification;

use Handymaster\SystemNotification\SystemNotification;
use Illuminate\Support\ServiceProvider;

class SystemNotificationServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/system-notification.php' => config_path('system-notification.php')
            ],'system-notification-config');

            $this->publishes([
                __DIR__.'/../resources/js' => resource_path('js'),
                __DIR__.'/../resources/sass' => resource_path('sass'),
                __DIR__.'/../resources/views' => resource_path('views'),

            ],'system-notification-resource');
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/system-notification.php', 'system-notification');

        // Register the service the package provides.
        $this->app->singleton('system-notification', SystemNotification::class);
    }
}
