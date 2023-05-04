<?php

namespace App\Core\Support\Facades;

use App\Core\Support\Adapters\ConstantAdapter;
use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed value(string $key)
 *
 * @see \App\Core\Support\Adapters\ConstantAdapter
 */
class Constants extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ConstantAdapter::class;
    }
}
