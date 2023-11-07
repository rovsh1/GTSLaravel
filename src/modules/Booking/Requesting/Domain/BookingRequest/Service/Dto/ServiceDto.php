<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Domain\BookingRequest\Service\Dto;

use Module\Shared\Enum\ServiceTypeEnum;

class ServiceDto
{
    public function __construct(
        public readonly string $title,
        public readonly string $typeName,
        public readonly ServiceTypeEnum $typeId,
    ) {}
}
