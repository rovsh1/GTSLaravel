<?php

declare(strict_types=1);

namespace Module\Booking\Notification\Domain\Service\VoucherGenerator\Dto;

class VoucherDto
{
    public function __construct(
        public readonly string $number,
        public readonly string $createdAt,
        public readonly string $fileUrl,
    ) {}
}
