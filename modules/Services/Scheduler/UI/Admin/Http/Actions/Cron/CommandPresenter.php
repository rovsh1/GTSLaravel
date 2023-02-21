<?php

namespace Module\Services\Scheduler\UI\Admin\Http\Actions\Cron;

class CommandPresenter
{
    private static array $timesAssoc = [
        '@weekly' => 'Еженедельно (02:00, Сб)',// '0 2 * * 6'],
        '@daily' => 'Ежедневно (01:00)',// '0 1 * * *'],
        '@hourly' => 'Ежедневно',// '0 * * * *']
    ];

    public static function format(string $time)
    {
        return self::$timesAssoc[$time]
            ?? self::weeklyPresentation($time)
            ?? self::dailyPresentation($time)
            ?? $time;
    }

    private static function weeklyPresentation($time): ?string
    {
        if (preg_match('/^(\d+) (\d+) \* \* (\d+)$/', $time, $m))
            return 'Еженедельно (' . self::str_pad($m[2]) . ':' . self::str_pad($m[1]) . ', ' . $m[3] . ')';
        else
            return null;
    }

    private static function dailyPresentation($time): ?string
    {
        if (preg_match('/^(\d+) (\d+) \* \* \*$/', $time, $m))
            return 'Ежедневно (' . self::str_pad($m[2]) . ':' . self::str_pad($m[1]) . ')';
        else
            return null;
    }

    private static function str_pad($s): string
    {
        return str_pad($s, 2, '0', STR_PAD_LEFT);
    }
}
