<?php

namespace App\Shared\Console\Commands\System\GarbageCollector;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearTempFiles extends Command
{
    protected $signature = 'system:clear-temp-files';

    protected $description = '';

    public function handle()
    {
        $dt = now();
        $dt->modify('-7 days');
        $t = $dt->getTimestamp();
        $storage = Storage::disk('tmp');
        $files = $storage->files('/');
        foreach ($files as $file) {
            $m = $storage->lastModified($file);
            if ($m < $t) {
                $storage->delete($file);
            }
        }
    }
}
