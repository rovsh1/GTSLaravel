<?php

namespace App\Hotel\Support\Facades;

use App\Hotel\Support\View\MetaManager;
use Gsdk\Meta\MetaCollection;
use Gsdk\Meta\Tag\Link;
use Gsdk\Meta\Tag\Script;
use Illuminate\Support\Facades\Facade;

/**
 * @method static MetaManager addScript(Script|string $script)
 * @method static MetaManager addStyle(Link|string $script)
 * @mixin MetaCollection
 */
class Meta extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MetaManager::class;
    }
}