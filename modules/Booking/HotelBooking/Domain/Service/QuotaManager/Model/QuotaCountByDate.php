<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager\Model;

use Carbon\CarbonInterface;

class QuotaCountByDate implements QuotaInterface
{
    public function __construct(
        public readonly int $roomId,
        public readonly int $count,
        public readonly CarbonInterface $date,
    ) {
    }

    public function roomId(): int
    {
        return $this->roomId;
    }

    public function date(): CarbonInterface
    {
        return $this->date;
    }
}
