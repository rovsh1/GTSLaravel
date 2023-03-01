<?php

namespace Gsdk\Meta\Head;

class Script extends AbstractMetaTag
{
    protected array $attributes = [
        //'type' => 'text/javascript',
        'async' => false,
        'defer' => false
    ];

    public function __construct($src, array $attributes = [])
    {
        $attributes['src'] = $src;
        $this->setAttributes($attributes);
    }

    public function getHtml(): string
    {
        return $this->getHtmlTag('script', true);
    }
}
