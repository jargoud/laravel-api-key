<?php

namespace Jargoud\LaravelApiKey;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jargoud\LaravelApiKey\Skeleton\SkeletonClass
 */
class LaravelApiKeyFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-api-key';
    }
}
