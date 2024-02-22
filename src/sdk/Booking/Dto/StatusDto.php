<?php

declare(strict_types=1);

namespace Sdk\Booking\Dto;

final class StatusDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $color,
    ) {}
}
