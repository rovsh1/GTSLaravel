<?php

namespace Module\Support\FileStorage\Console\Service\HealthChecker;

use Module\Support\FileStorage\Console\Service\HealthChecker\Dto\BrokenGuidDto;
use Module\Support\FileStorage\Console\Service\HealthChecker\Dto\HealthCheckResultDto;
use Module\Support\FileStorage\Domain\Entity\File;
use Module\Support\FileStorage\Domain\Service\PathGeneratorInterface;
use Module\Support\FileStorage\Domain\ValueObject\Guid;
use Module\Support\FileStorage\Infrastructure\Model\File as Model;

class HealthChecker
{
    public function __construct(
        private readonly GuidReferenceFinder $guidReferenceChecker,
        private readonly PathGeneratorInterface $pathGenerator,
    ) {
    }

    public function check(): HealthCheckResultDto
    {
        $brokenGuids = [];
        foreach (Model::query()->cursor() as $r) {
            $usage = $this->guidReferenceChecker->findUsage($r->guid);
            $fileExists = $this->exists($r->guid, $r->name);

            if (!$fileExists || empty($usage)) {
                $brokenGuids[] = new BrokenGuidDto(
                    guid: $r->guid,
                    isNotExists: !$fileExists,
                    usage: $usage
                );
            }
        }

        return new HealthCheckResultDto(
            $brokenGuids
        );
    }

    private function exists(string $guid, string $name): bool
    {
        $filename = $this->pathGenerator->path(
            new File(new Guid($guid), $name)
        );

        return file_exists($filename);
    }
}
