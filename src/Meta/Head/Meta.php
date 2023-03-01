<?php

namespace Gsdk\Meta\Head;

class Meta extends AbstractMetaTag
{
    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    public function getIdentifier(): ?string
    {
        if (isset($this->attributes['http-equiv'])) {
            return 'meta_http_equiv_' . $this->getAttribute('http-equiv');
        } elseif (isset($this->attributes['name'])) {
            return 'meta_name_' . $this->getAttribute('name');
        } elseif (isset($this->attributes['property'])) {
            return 'meta_property_' . $this->getAttribute('property');
        } else {
            return null;
        }
    }

    public function getHtml(): string
    {
        return $this->getHtmlTag('meta', false);
    }
}
