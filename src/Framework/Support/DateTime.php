<?php

namespace Custom\Framework\Support;

use Carbon\Carbon;

class DateTime extends Carbon implements DateTimeInterface
{
    private static array $formats = [
        'date' => 'd.m.Y',
        'time' => 'H:i',
        'datetime' => 'd.m.Y H:i'
    ];

    private static array $formatModifiers = [];

    private static function addFormatModifier($char, $callback)
    {
        static::$formatModifiers[$char] = $callback;
    }

    public static function setFormats(array $formats)
    {
        static::$formats = $formats;

        static::addFormatModifier('F', fn($date) => $date->monthName);
    }

    public static function setFormat(string $name, string $format)
    {
        static::$formats[$name] = $format;
    }

    public function format($format)
    {
        if (isset(static::$formats[$format])) {
            $format = static::$formats[$format];
        }

        $modifiers = [];
        $modifierKeys = array_keys(static::$formatModifiers);
        foreach ($modifierKeys as $i => $c) {
            if (!str_contains($format, $c)) {
                continue;
            }
            $fn = static::$formatModifiers[$c];
            $modifiers[$i] = $fn($this);
            $format = str_replace($c, '$' . $i, $format);
        }

        $format = parent::format($format);

        foreach ($modifiers as $i => $v) {
            $format = str_replace('$' . $i, $v, $format);
        }

        return $format;
    }
}
