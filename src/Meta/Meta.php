<?php

namespace Gsdk\Meta;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Gsdk\Meta\MetaTags fromString(string $html)
 * @method static \Gsdk\Meta\MetaTags title(string $title)
 * @method static \Gsdk\Meta\MetaTags description(string $description)
 * @method static \Gsdk\Meta\MetaTags keywords(string $keywords)
 * @method static \Gsdk\Meta\MetaTags baseHref(string $href)
 * @method static \Gsdk\Meta\MetaTags addContent(string $content)
 * @method static \Gsdk\Meta\MetaTags addMeta(array $attributes)
 * @method static \Gsdk\Meta\MetaTags addLink(array $attributes)
 * @method static \Gsdk\Meta\MetaTags addLinkRel(string $rel, $href, array $attributes = [])
 * @method static \Gsdk\Meta\MetaTags addMetaName(string $name, string $content, array $attributes = [])
 * @method static \Gsdk\Meta\MetaTags addMetaProperty(string $property, string $content, array $attributes = [])
 * @method static \Gsdk\Meta\MetaTags addMetaHttpEquiv(string $keyValue, string $content, array $attributes = [])
 * @method static \Gsdk\Meta\MetaTags addStyle(string $href, array $attributes = [])
 * @method static \Gsdk\Meta\MetaTags addScript(string $src, array $attributes = [])
 * @method static string render()
 */
class Meta extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'meta';
    }
}
