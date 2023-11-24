<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Application\Dto;

final class StatusDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $color,
    ) {}
}
