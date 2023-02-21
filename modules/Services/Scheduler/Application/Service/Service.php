<?php

namespace Module\Services\Scheduler\Application\Service;

//use Ustabor\Infrastructure\Models\System\Cron;

use GTS\Services\Scheduler\Application\Service\Cron;

class Service
{

    const STATUS_OK = 1;
    const STATUS_FAILED = 2;
    const STATUS_PROCESSING = 3;
    const TAB_CHAR = ' ';

    public static function runBackground(Cron $cron)
    {
        /*$locale = 'ru_RU.UTF-8';
        setlocale(LC_ALL, $locale);
        putenv('LC_ALL=' . $locale);*/
        $cron->update([
            'last_status' => self::STATUS_PROCESSING,
            //'last_executed' => null,
            'last_log' => null
        ]);

        $command = self::getCronCommand($cron, true);
        //dd($command);
        $command .= ' > /dev/null 2>/dev/null';
        $command .= ' & echo $!';
        $pid = shell_exec($command);
    }

    public static function run(Cron $cron)
    {
        ignore_user_abort(true);
        set_time_limit(0);
        $cron->update([
            'last_status' => self::STATUS_PROCESSING,
            'last_log' => null
        ]);
        /*$locale = 'ru_RU.UTF-8';
        setlocale(LC_ALL, $locale);
        putenv('LC_ALL=' . $locale);*/
        $command = self::getCronCommand($cron);
        $response = shell_exec($command) ?: null;

        $cron->update([
            'last_executed' => now(),
            'last_status' => $response === null ? self::STATUS_OK : self::STATUS_FAILED,
            'last_log' => trim($response)
        ]);
    }

    private static function formatTime($command)
    {
        static $commandsAssoc = [
            '@weekly' => '0 2 * * 6',
            '@daily' => '0 1 * * *',
            '@hourly' => '0 * * * *'
        ];

        foreach ($commandsAssoc as $k => $v) {
            $command = str_replace($k, $v, $command);
        }

        return $command;
    }

    private static function formatCommand(string $command): string
    {
        $commandsAssoc = [
            '@console' => base_path('bin/artisan')
        ];

        foreach ($commandsAssoc as $k => $v) {
            $command = str_replace($k, $v, $command);
        }

        return $command;
    }

    public static function getCronCommand(Cron $cron, $background = false): string
    {
        if ($background) {
            $command = 'php -q ';//nohup
            $command .= self::formatCommand('@console cron:job ' . $cron->id);
            //$command .= self::formatCommand($this->command);
            //$command .= ' > /dev/null 2>&1';
            //$command .= ' 2> /dev/null & echo $!';
        } else {
            $command = 'php ';//nohup
            $command .= self::formatCommand('@console ' . $cron->command . ($cron->arguments ? ' ' . $cron->arguments : ''));
        }

        return $command;
    }

    public static function updateCrontabFile()
    {
        $filename = storage_path('tmp/crontab');

        $s = '';
        foreach (Cron::where('enabled', true)->get() as $cron) {
            $s .= self::formatTime($cron->time) . self::TAB_CHAR;
            //$s .= 'dev' . self::TAB_CHAR;
            $s .= self::getCronCommand($cron, true) . "\n";
        }

        $h = fopen($filename, 'w+');
        fwrite($h, $s);
        fclose($h);

        shell_exec('crontab ' . $filename);

        unlink($filename);
    }

}
