<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\Service\Dto;

use Illuminate\Support\Collection;
use Module\Booking\Invoicing\Domain\Service\Dto\Service\BookingPeriodDto;
use Module\Booking\Invoicing\Domain\Service\Dto\Service\GuestDto;
use Module\Booking\Invoicing\Domain\Service\Dto\Service\PriceDto;

class ServiceInfoDto
{
    public function __construct(
        public readonly string $title,
        public readonly BookingPeriodDto $bookingPeriod,
        public readonly Collection $detailOptions,
        /** @var GuestDto[] $guests */
        public readonly array $guests,
        public readonly PriceDto $price
    ) {}
}
