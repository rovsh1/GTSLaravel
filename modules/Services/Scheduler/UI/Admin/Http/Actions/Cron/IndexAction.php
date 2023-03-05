<?php

namespace Module\Services\Scheduler\UI\Admin\Http\Actions\Cron;

use App\Admin\Support\View\Grid\Grid;
use Illuminate\Support\Facades\Artisan;

class IndexAction
{
    public function handle()
    {
        //$commands = self::getCommands();

        return app('layout')
            ->ss('cron/index')
            ->title('Крон задания')
            ->view('cron.index', [
                'grid' => $this->gridFactory()
            ]);
    }

    private static function getCommands(): array {
        $commands = array_filter(Artisan::all(), function ($command) {
            return str_starts_with($command::class, 'Ustabor\Domain\Console')
                && isset($command->cronable);
        });

        $array = [];
        foreach ($commands as $key => $command) {
            $d = $command->getDescription();
            $array[$key] = $key . ($d ? ' (' . $d . ')' : '');
        }

        asort($array);

        return $array;
    }

    private function gridFactory()
    {
        return (new Grid())
            ->paginator(30)
            ->addColumn('edit')
            ->addColumn('enabled', 'boolean', ['text' => 'Включена'])
            //->addColumn('command', 'text', ['text' => 'Комманда'])
            ->addColumn('command', 'text', [
                'text' => 'Комманда',
                'renderer' => fn($row) => $commands[$row->command] ?? ''
            ])
            ->addColumn('time', 'text', [
                'text' => 'Время',
                'renderer' => fn($row) => CommandPresenter::format($row->time)
            ])
            //->addColumn('user', 'text', ['text' => 'Пользователь'])
            ->addColumn('log', 'text', [
                'text' => 'Последнее выполнение',
                'renderer' => fn($row) => self::getStatusHtml($row)
            ])
            ->addColumn('run', 'text', [
                'text' => '',
                'renderer' => fn($row) => '<a href="#" class="" data-status="' . $row->last_status . '" data-id="' . $row->id . '">Запустить</a>'
            ]);
    }

    private static function getStatusHtml($job): string
    {
        if (!$job->last_executed)
            return '';

        return match ($job->last_status) {
            1 => '<span class="valid">' . $job->last_executed->format('datetime') . '</span>',
            2 => '<span class="invalid" title="' . htmlspecialchars($job->last_log) . '">' . $job->last_executed->format('datetime') . '</span>',
            3 => '<span class="">PROCESSING</span>',
            default => '',
        };
    }

    public function breadcrumbs() {
        return parent::breadcrumbs()
            ->add('Крон задания', 'system.cron.index');
    }
}
