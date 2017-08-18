<?php

namespace Handymaster\SystemNotification\Facade;

use Illuminate\Support\Facades\Facade;

class SystemNotification extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'system-notification';
    }
}
