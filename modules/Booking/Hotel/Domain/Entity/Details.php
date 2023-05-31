<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Entity;

use Module\Booking\Common\Domain\Entity\Details\HotelDetailsInterface;
use Module\Booking\Hotel\Domain\Entity\Details\Room;
use Module\Booking\Hotel\Domain\Entity\Details\RoomCollection;
use Module\Booking\Hotel\Domain\ValueObject\Details\AdditionalInfo;
use Module\Booking\Hotel\Domain\ValueObject\Details\BookingPeriod;

final class Details implements HotelDetailsInterface
{
    public function __construct(
        private readonly int $id,
        private readonly int $hotelId,
        private readonly BookingPeriod $period,
        private ?AdditionalInfo $additionalInfo,
        private readonly RoomCollection $rooms
    ) {}

    //@todo из квот release_days, до даты booking_start_date - release_days (бесплатно)
    //@todo добавить условия отмены из отеля при создании

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

    public function setAdditionalInfo(?AdditionalInfo $additionalInfo): void
    {
        $this->additionalInfo = $additionalInfo;
    }

    public function rooms(): RoomCollection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): void
    {
        $this->rooms->add($room);
    }

    public function updateRoom(int $index, Room $room): void
    {
        $this->rooms->offsetSet($index, $room);
    }

    public function deleteRoom(int $index): void
    {
        $this->rooms->offsetUnset($index);
    }
}
