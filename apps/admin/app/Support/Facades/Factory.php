<?php

namespace App\Admin\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Admin\Components\Factory\PrototypesCollection prototypes()
 *
 * @see \App\Admin\Components\Factory\FactoryManager
 */
class Factory extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'factory';
    }
}
