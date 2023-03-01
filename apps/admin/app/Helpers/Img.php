<?php

namespace App\Admin\Helpers;

class Img
{
    public static string $noPhotoSrc = '';

    public static string $emptyAvatarSrc = '';

    public static function blankSrc(): string
    {
        return 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=';
    }

    public static function blank(array $attributes = []): string
    {
        return self::src(self::blankSrc(), $attributes);
    }

    public static function noPhotoSrc(): string
    {
        return self::$noPhotoSrc;
    }

    public static function noPhoto(array $attributes = []): string
    {
        return self::src(self::noPhotoSrc(), $attributes);
    }

    public static function emptyAvatarSrc(): string
    {
        return self::$emptyAvatarSrc;
    }

    public static function emptyAvatar(array $attributes = []): string
    {
        return self::src(self::emptyAvatarSrc(), $attributes);
    }

    public static function image(?string $guid, int $part = null, array $attributes = []): string
    {
        if (empty($guid)) {
            return self::noPhoto($attributes);
        } else {
            return self::src($guid, $attributes);
        }
    }

    public static function avatar(?string $guid, array $attributes = []): string
    {
        if (empty($guid)) {
            return self::emptyAvatar($attributes);
        } else {
            return self::src($guid, $attributes);
        }
    }

    public static function src(string $src, array $attributes = []): string
    {
        return self::tag('img', array_merge($attributes, ['src' => $src]));
    }

    private static function tag(string $tag, array $attributes, bool $closeTag = false): string
    {
        $html = '<' . $tag;

        foreach ($attributes as $k => $v) {
            if (is_string($k)) {
                $html .= ' ' . $k . '="' . $v . '"';
            } else {
                $html .= ' ' . $k;
            }
        }

        $html .= '>';

        if ($closeTag) {
            $html .= '</' . $tag . '>';
        }

        return $html;
    }
}
