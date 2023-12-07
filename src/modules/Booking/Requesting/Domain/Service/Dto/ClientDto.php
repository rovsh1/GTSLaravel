<?php

declare(strict_types=1);

namespace Module\Booking\Requesting\Domain\Service\Dto;

class ClientDto
{
    public function __construct(
        public readonly string $name,
    ) {}
}
