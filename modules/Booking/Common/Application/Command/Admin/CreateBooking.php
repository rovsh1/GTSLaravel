<?php

declare(strict_types=1);

namespace Module\Booking\Common\Application\Command\Admin;

use Carbon\CarbonPeriod;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Sdk\Module\Contracts\Bus\CommandInterface;

class CreateBooking implements CommandInterface
{
    public function __construct(
        public readonly int $cityId,
        public readonly int $clientId,
        public readonly int $hotelId,
        public readonly CarbonPeriod $period,
        public readonly int $creatorId,
        public readonly BookingTypeEnum $type,
        public readonly ?int $orderId,
        public readonly ?string $note = null
    ) {}
}
