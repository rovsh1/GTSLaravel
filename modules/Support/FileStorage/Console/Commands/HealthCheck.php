<?php

namespace Module\Support\FileStorage\Console\Commands;

use Module\Support\FileStorage\Console\Service\HealthChecker\Dto\BrokenGuidDto;
use Module\Support\FileStorage\Console\Service\HealthChecker\Dto\HealthCheckResultDto;
use Module\Support\FileStorage\Console\Service\HealthChecker\HealthChecker;
use Module\Support\FileStorage\Console\Support\AbstractCommand;

class HealthCheck extends AbstractCommand
{
    protected $signature = 'file-storage:check
    {--clean}
    {--fix}';

    protected $description = '';

    public function handle(): void
    {
        $healthChecker = $this->make(HealthChecker::class);
        $result = $healthChecker->check();

        $this->outputResult($result);

        if ($this->option('clean')) {
            $this->call(CleanStorage::class);
        }

        if ($this->option('fix')) {
            $this->call(FixBroken::class);
        }
    }

    private function outputResult(HealthCheckResultDto $result): void
    {
        if ($result->isOk) {
            $this->info('File storage health checked: OK');

            return;
        }

        $this->error('File storage health checked: Has Errors');

//            $this->comment('  ' . $title . ':');
//        $this->output->listing($values);
        /** @var BrokenGuidDto $guidDto */
        foreach ($result->brokenGuids as $guidDto) {
            $this->output->write("    <comment>$guidDto->guid</comment> -");

            if ($guidDto->isNotExists) {
                $this->output->write(' <error>NotExists</error>');
            }

            if ($guidDto->isUnused) {
                $this->output->write(' <error>Unused</error>');
            } else {
                $usage = implode(', ', array_map(fn($r) => "$r->table.$r->column", $guidDto->usage));
                $this->output->write(" (Used in $usage)");
            }

            $this->output->newLine();
        }
    }
}