<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Dto;

class VoucherDto
{
    public function __construct(
        public readonly string $number,
        public readonly string $createdAt,
    ) {}
}
