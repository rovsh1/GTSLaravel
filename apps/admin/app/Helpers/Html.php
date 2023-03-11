<?php

namespace App\Admin\Helpers;

class Html
{
    public static function img(string $src, array $attributes = []): string
    {
        return self::closedTag('img', array_merge($attributes, ['src' => $src]));
    }

    public static function a(string $href, string $text, array $attributes = []): string
    {
        return self::openedTag('a', $text, array_merge($attributes, ['href' => $href]));
    }

    public static function attributesToString(array $attributes): string
    {
        $html = '';
        foreach ($attributes as $k => $v) {
            if (is_string($k)) {
                $html .= ' ' . $k . '="' . $v . '"';
            } else {
                $html .= ' ' . $k;
            }
        }
        return $html;
    }

    private static function openedTag(string $tag, string $content, array $attributes): string
    {
        return '<' . $tag . self::attributesToString($attributes) . '>' . $content . '</' . $tag . '>';
    }

    private static function closedTag(string $tag, array $attributes): string
    {
        return '<' . $tag . self::attributesToString($attributes) . '>';
    }
}
