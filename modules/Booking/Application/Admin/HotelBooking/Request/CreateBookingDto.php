<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\Request;

use Carbon\CarbonPeriod;
use Module\Shared\Enum\CurrencyEnum;

class CreateBookingDto
{
    public function __construct(
        public readonly int $cityId,
        public readonly int $clientId,
        public readonly ?int $legalId,
        public readonly CurrencyEnum $currency,
        public readonly int $hotelId,
        public readonly CarbonPeriod $period,
        public readonly int $creatorId,
        public readonly int $quotaProcessingMethod,
        public readonly ?int $orderId,
        public readonly ?string $note = null
    ) {}
}
