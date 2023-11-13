<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\Service\Dto;

class ManagerDto
{
    public function __construct(
        public readonly string $fullName,
        public readonly ?string $email,
        public readonly ?string $phone,
    ) {}
}
