<?php

namespace Gsdk\Meta\Head;

abstract class AbstractMetaTag implements MetaTagInterface
{
    protected array $attributes = [];

    public function __get($name)
    {
        return $this->getAttribute($name);
    }

    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $k => $v) {
            $this->setAttribute($k, $v);
        }
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttribute($name, $value): void
    {
        $this->attributes[$name] = $value;
    }

    public function getAttribute(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function getIdentifier(): ?string
    {
        return $this->attributes['id'] ?? null;
    }

    protected function getHtmlTag($tag, $close = false): string
    {
        $s = '<' . $tag;
        foreach ($this->attributes as $k => $v) {
            if (is_bool($v)) {
                if ($v) {
                    $s .= ' ' . $k;
                }
            } elseif ($v) {
                $s .= ' ' . $k . '="' . $v . '"';
            }
        }
        $s .= ($close ? '></' . $tag . '>' : '>');
        return $s;
    }

    abstract public function getHtml(): string;

    public function __toString()
    {
        return $this->getHtml();
    }
}
