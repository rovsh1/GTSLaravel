<?php

namespace App\Core\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Module\Shared\Domain\Adapter\ConstantAdapterInterface;
use Module\Shared\Infrastructure\Adapter\ConstantAdapter;

/**
 * @method static mixed value(string $key)
 *
 * @see ConstantAdapter
 */
class Constants extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ConstantAdapterInterface::class;
    }
}
