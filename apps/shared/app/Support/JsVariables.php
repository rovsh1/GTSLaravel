<?php

namespace App\Shared\Support;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Js;

class JsVariables implements Htmlable
{
    public function __construct(private array $values = []) {}

    public function toHtml(): string
    {
        if (empty($this->values)) {
            return '';
        }

        return '<script>'
            . 'window["view-initial-data"] = ' . Js::from($this->values) . ';'
            . '</script>';
    }

    public function __toString()
    {
        return $this->toHtml();
    }
}