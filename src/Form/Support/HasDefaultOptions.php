<?php

namespace Gsdk\Form\Support;

trait HasDefaultOptions
{
    protected static array $defaultOptions = [];

    public static function setDefaults(array $options): void
    {
        static::$defaultOptions = $options;
    }
}
