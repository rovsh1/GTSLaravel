<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\Service\VoucherGenerator\Dto;

class OrderDto
{
    public function __construct(
        public readonly string $number,
        public readonly string $currency,
    ) {}
}