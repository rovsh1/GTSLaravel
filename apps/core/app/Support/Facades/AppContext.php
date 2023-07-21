<?php

namespace App\Core\Support\Facades;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use Module\Shared\Enum\SourceEnum;

/**
 * @method static void setSource(SourceEnum $source)
 * @method static SourceEnum getSource()
 * @method static void setRequest(Request $request)
 * @method static void setUser(int $id, string $name = null)
 * @method static void setAdministrator(int $id, string $name)
 * @method static void set(string $key, mixed $value)
 * @method static array get()
 *
 * @see \App\Core\Components\Context\AppContext
 */
class AppContext extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'app-context';
    }
}
