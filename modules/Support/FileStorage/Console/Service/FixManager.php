<?php

namespace Module\Support\FileStorage\Console\Service;

use Illuminate\Support\Facades\DB;
use Module\Support\FileStorage\Application\UseCase\DeleteFile;
use Module\Support\FileStorage\Console\Service\HealthChecker\Dto\BrokenGuidDto;
use Module\Support\FileStorage\Console\Service\HealthChecker\Dto\GuidReferenceDto;
use Module\Support\FileStorage\Console\Service\HealthChecker\HealthChecker;

class FixManager
{
    public function __construct(
        private readonly HealthChecker $healthChecker
    ) {
    }

    public function fix(): void
    {
        $result = $this->healthChecker->check();

        $useCase = app(DeleteFile::class);
        /** @var BrokenGuidDto $guidDto */
        foreach ($result->brokenGuids as $guidDto) {
            if (!$guidDto->isUnused) {
                $this->nullReferences($guidDto);
            }
            $useCase->execute($guidDto->guid);
        }
    }

    private function nullReferences(BrokenGuidDto $guidDto): void
    {
        /** @var GuidReferenceDto $usage */
        foreach ($guidDto->usage as $usage) {
            DB::table($usage->table)
                ->where($usage->column, $guidDto->guid)
                ->update([$usage->column => null]);
        }
    }
}