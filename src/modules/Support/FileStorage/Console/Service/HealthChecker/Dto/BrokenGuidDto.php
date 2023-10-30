<?php

namespace Module\Support\FileStorage\Console\Service\HealthChecker\Dto;

final class BrokenGuidDto
{
    public readonly bool $isUnused;

    public function __construct(
        public readonly string $guid,
        public readonly bool $isNotExists,
        public readonly array $usage,
    ) {
        $this->isUnused = empty($this->usage);
    }
}
