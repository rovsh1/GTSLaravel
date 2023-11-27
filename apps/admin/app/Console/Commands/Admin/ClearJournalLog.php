<?php

namespace App\Admin\Console\Commands\Admin;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearJournalLog extends Command
{
    private const DEFAULT_TTL = '2 weeks';

    protected $signature = 'system:clear-journal-log
    {--from=}';

    protected $description = '';

    public function handle(): void
    {
        $from = now();
        $from->modify('-' . self::DEFAULT_TTL);

        DB::table('administrator_journal_log')
            ->where('created_at', '<=', $from->format('Y-m-d H:i:s'))
            ->detete();
    }
}
