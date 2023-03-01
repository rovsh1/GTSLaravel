<?php

namespace Gsdk\Meta\Head;

class BaseHref extends AbstractMetaTag
{
    public function __construct($href, array $attributes = [])
    {
        $attributes['href'] = $href;
        $this->setAttributes($attributes);
    }

    public function getIdentifier(): ?string
    {
        return 'base';
    }

    public function getHtml(): string
    {
        return $this->getHtmlTag('base', false);
    }
}
