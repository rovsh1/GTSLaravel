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
        return Html::img(self::blankSrc(), $attributes);
    }

    public static function noPhotoSrc(): string
    {
        return self::$noPhotoSrc;
    }

    public static function noPhoto(array $attributes = []): string
    {
        return Html::img(self::noPhotoSrc(), $attributes);
    }

    public static function emptyAvatarSrc(): string
    {
        return self::$emptyAvatarSrc;
    }

    public static function emptyAvatar(array $attributes = []): string
    {
        return Html::img(self::emptyAvatarSrc(), $attributes);
    }

    public static function image(?string $guid, int $part = null, array $attributes = []): string
    {
        if (empty($guid)) {
            return self::noPhoto($attributes);
        } else {
            return Html::img($guid, $attributes);
        }
    }

    public static function avatar(?string $guid, array $attributes = []): string
    {
        if (empty($guid)) {
            return self::emptyAvatar($attributes);
        } else {
            return Html::img($guid, $attributes);
        }
    }
}
