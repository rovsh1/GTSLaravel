<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\Command\Admin;

use Carbon\CarbonPeriod;
use Custom\Framework\Contracts\Bus\CommandInterface;

class CreateBooking implements CommandInterface
{
    public function __construct(
        public readonly int $cityId,
        public readonly int $clientId,
        public readonly int $hotelId,
        public readonly CarbonPeriod $period,
        public readonly int $creatorId,
        public readonly ?int $orderId,
        public readonly ?string $note = null
    ) {}
}
