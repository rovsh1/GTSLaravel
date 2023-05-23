<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Entity;

use Module\Booking\Common\Domain\Entity\Details\HotelDetailsInterface;
use Module\Booking\Hotel\Domain\Entity\Details\RoomCollection;
use Module\Booking\Hotel\Domain\ValueObject\Details\AdditionalInfo;
use Module\Booking\Hotel\Domain\ValueObject\Details\BookingPeriod;

final class Details implements HotelDetailsInterface
{
    public function __construct(
        private readonly int $id,
        private readonly int $hotelId,
        private readonly BookingPeriod $period,
        private readonly ?AdditionalInfo $additionalInfo,
        private readonly RoomCollection $rooms
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function hotelId(): int
    {
        return $this->hotelId;
    }

    public function period(): BookingPeriod
    {
        return $this->period;
    }

    public function additionalInfo(): ?AdditionalInfo
    {
        return $this->additionalInfo;
    }

    public function rooms(): RoomCollection
    {
        return $this->rooms;
    }
}
