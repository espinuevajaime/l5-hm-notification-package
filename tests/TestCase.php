<?php

namespace Handymaster\SystemNotification\Tests;

use Handymaster\SystemNotification\SystemNotificationServiceProvider;
use Handymaster\SystemNotification\Facade\SystemNotification;


abstract class TestCase extends \Orchestra\TestBench\TestCase
{
    /**
     * Setup test
     */
    protected function setUp($app): void
    {
        parent::setUp();



    }

    protected function getEnvironmentSetUp($app)
    {
        $app->make('Illuminate\Contracts\Http\Kernel')->pushMiddleware('Illuminate\Session\Middleware\StartSession');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
          SystemNotificationServiceProvider::class
        ];
    }

    protected function getApplicationAliases($app)
    {
        return [
            'SystemNotification' => SystemNotification::class
        ];
    }
}
