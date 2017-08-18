<?php

namespace Handymaster\SystemNotification;

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
            //
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/system-notification.php', 'hm-system-notification');

        // Register the service the package provides.
        $this->app->singleton('system-notification', SysNotificationRepository::class);
    }
}
