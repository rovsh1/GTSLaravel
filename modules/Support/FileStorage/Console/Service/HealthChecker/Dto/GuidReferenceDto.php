<?php

namespace Module\Support\FileStorage\Console\Service\HealthChecker\Dto;

final class GuidReferenceDto
{
    public function __construct(
        public readonly string $table,
        public readonly string $column,
    ) {
    }
}
