<?php

namespace Gsdk\Meta\Head;

class Style extends AbstractMetaTag
{
    protected array $attributes = [
        'rel' => 'stylesheet',
        'type' => 'text/css',
        'media' => 'screen'
    ];

    public function __construct($href, array $attributes = [])
    {
        $attributes['href'] = $href;
        $this->setAttributes($attributes);
    }

    public function getHtml(): string
    {
        return $this->getHtmlTag('link', false);
    }
}
