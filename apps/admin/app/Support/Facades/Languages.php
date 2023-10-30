<?php

namespace App\Admin\Support\Facades;

use App\Shared\Components\Locale\Language;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array all()
 * @method static array map(callable $fn)
 * @method static Language default()
 * @method static Language get(string $code)
 * @method static bool has(string $code)
 *
 * @see \App\Shared\Components\Locale\Languages
 */
class Languages extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'languages';
    }
}
