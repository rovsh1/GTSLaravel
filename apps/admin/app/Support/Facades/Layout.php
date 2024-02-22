<?php

namespace App\Admin\Support\Facades;

use App\Admin\Support\View\Layout as LayoutBuilder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Facade;

/**
 * @method static LayoutBuilder title(string $title)
 * @method static LayoutBuilder h1(string $h1)
 * @method static LayoutBuilder data(array $data)
 * @method static LayoutBuilder view(string $view, array $data = [])
 * @method static string getTitle()
 * @method static mixed get(string $name)
 * @method static void configure()
 * @method static View render()
 * @method static string renderMeta()
 */
class Layout extends Facade
{
    protected static function getFacadeAccessor()
    {
        return LayoutBuilder::class;
    }
}
