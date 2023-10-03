<?php

declare(strict_types=1);

namespace Module\Booking\Domain\HotelBooking\Service\QuotaManager\Model;

use Carbon\CarbonInterface;
use Module\Booking\Domain\HotelBooking\ValueObject\QuotaId;

class RoomDateQuotaReservation implements QuotaInterface
{
    public function __construct(
        public readonly int $roomId,
        public readonly int $count,
        public readonly CarbonInterface $date,
        private ?QuotaId $quotaId = null
    ) {}

    public function roomId(): int
    {
        return $this->roomId;
    }

    public function date(): CarbonInterface
    {
        return $this->date;
    }

    public function quotaId(): ?QuotaId
    {
        return $this->quotaId;
    }

    public function setQuotaId(?QuotaId $quotaId): void
    {
        $this->quotaId = $quotaId;
    }
}
