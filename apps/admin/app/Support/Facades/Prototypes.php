<?php

namespace App\Admin\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array all()
 * @method static \App\Admin\Components\Factory\Prototype get(string $key)
 * @method static bool has(string $key)
 *
 * @see \App\Admin\Components\Factory\PrototypesCollection
 */
class Prototypes extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'factory.prototypes';
    }
}
