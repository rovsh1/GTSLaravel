<?php

namespace App\Admin\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Admin\Support\View\Layout title(string $title)
 * @method static \App\Admin\Support\View\Layout description(string $description)
 * @method static \App\Admin\Support\View\Layout keywords(string $keywords)
 * @method static \App\Admin\Support\View\Layout h1(string $h1)
 * @method static \App\Admin\Support\View\Layout ss(string $src)
 * @method static \App\Admin\Support\View\Layout style(string $src)
 * @method static \App\Admin\Support\View\Layout script(string $src)
 * @method static \App\Admin\Support\View\Layout baseHref(string $href)
 * @method static \App\Admin\Support\View\Layout addContent(string $content)
 * @method static \App\Admin\Support\View\Layout addMeta(array $attributes)
 * @method static \App\Admin\Support\View\Layout addLink(array $attributes)
 * @method static \App\Admin\Support\View\Layout addLinkRel(string $rel, $href, array $attributes = [])
 * @method static \App\Admin\Support\View\Layout addMetaName(string $name, string $content, array $attributes = [])
 * @method static \App\Admin\Support\View\Layout addMetaProperty(string $property, string $content, array $attributes = [])
 * @method static \App\Admin\Support\View\Layout addMetaHttpEquiv(string $keyValue, string $content, array $attributes = [])
 * @method static \App\Admin\Support\View\Layout addStyle(string $href, array $attributes = [])
 * @method static \App\Admin\Support\View\Layout addScript(string $src, array $attributes = [])
 * @method static \App\Admin\Support\View\Layout addMetaVariable(string $name, mixed $value)
 * @method static \App\Admin\Support\View\Layout data(array $data)
 * @method static \App\Admin\Support\View\Layout view(string $view, array $data = [])
 * @method static mixed get(string $name)
 * @method static void configure()
 * @method static \Illuminate\Contracts\View\View render()
 *
 * @see \App\Admin\Support\View\Layout
 */
class Layout extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'layout';
    }
}
