<?php

namespace Gsdk\Form\Support;

trait HasDefaults
{
    protected static array $defaultOptions = [];

    public static function setDefaults(array $options): void
    {
        static::$defaultOptions = $options;
    }
}
