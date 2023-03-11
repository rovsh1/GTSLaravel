<?php

namespace App\Admin\Helpers;

class Icon
{
    private static array $assocIcons = [
        //'edit' => 'pencil',
    ];

    private static array $categoryIcons = [
        'administration' => 'settings',
        'client' => 'person',
        'finances' => 'payments',
        'hotel' => 'hotel',
        'reports' => 'bar_chart',
        'reservation' => 'airplane_ticket',
        'site' => 'language',
    ];

    public static function __callStatic(string $name, array $arguments)
    {
        if (isset(self::$assocIcons[$name])) {
            return self::getIcon(self::$assocIcons[$name]);
        } else {
            return self::getIcon($name);
        }
    }

    public static function category(string $key): string
    {
        return self::getIcon(self::$categoryIcons[$key]);
    }

    public static function getIcon(string $key): string
    {
        return '<i class="icon">' . $key . '</i>';
    }
}
