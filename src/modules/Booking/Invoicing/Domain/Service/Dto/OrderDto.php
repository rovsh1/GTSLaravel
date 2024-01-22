<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\Service\Dto;


use Module\Booking\Invoicing\Domain\Service\Dto\Service\GuestDto;

class OrderDto
{
    public function __construct(
        public readonly string $number,
        public readonly string $currency,
        /** @var GuestDto[] $guests */
        public readonly array $guests,
        public readonly ?OrderPeriodDto $period
    ) {}
}
