<?php

namespace Gsdk\Meta\Head;

class Link extends AbstractMetaTag
{
    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    public function getHtml(): string
    {
        return $this->getHtmlTag('link', false);
    }
}
