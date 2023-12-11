<?php

namespace App\Shared\Support;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Js;

class JsVariables implements Htmlable
{
    public function __construct(private array $values = [], private ?string $namespace = null) {}

    public function namespace(string $namespace): static
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function toHtml(): string
    {
        if (empty($this->values)) {
            return '';
        }

        return '<script>'
            . 'window["view-initial-data' . (!empty($this->namespace) ? "-$this->namespace" : '') . '"] = '
            . Js::from($this->values)
            . ';</script>';
    }

    public function __toString()
    {
        return $this->toHtml();
    }
}