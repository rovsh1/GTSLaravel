<?php

namespace Module\Support\FileStorage\Console\Service\HealthChecker\Dto;

final class HealthCheckResultDto
{
    public readonly bool $isOk;

    public function __construct(
        public readonly array $brokenGuids,
    ) {
        $this->isOk = empty($brokenGuids);
    }
}
