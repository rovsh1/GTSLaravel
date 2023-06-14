<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Entity;

use Carbon\CarbonImmutable;
use Module\Booking\Common\Domain\Entity\AbstractBooking;
use Module\Booking\Common\Domain\ValueObject\BookingStatusEnum;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Hotel\Domain\ValueObject\Details\AdditionalInfo;
use Module\Booking\Hotel\Domain\ValueObject\Details\BookingPeriod;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelConditions;
use Module\Booking\Hotel\Domain\ValueObject\Details\HotelInfo;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBookingCollection;
use Module\Shared\Domain\ValueObject\Id;

final class Booking extends AbstractBooking
{
    public function __construct(
        Id $id,
        Id $orderId,
        BookingStatusEnum $status,
        BookingTypeEnum $type,
        CarbonImmutable $createdAt,
        Id $creatorId,
        private ?string $note = null,
        private HotelInfo $hotelInfo,
        private BookingPeriod $period,
        private ?AdditionalInfo $additionalInfo,
        private RoomBookingCollection $roomBookings,
        private CancelConditions $cancelConditions
    ) {
        parent::__construct($id, $orderId, $status, $type, $createdAt, $creatorId);
    }

    public function hotelInfo(): HotelInfo
    {
        return $this->hotelInfo;
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

    public function roomBookings(): RoomBookingCollection
    {
        return $this->roomBookings;
    }

    public function addRoomBooking(RoomBooking $booking): void
    {
        $this->roomBookings->add($booking);
    }

    public function updateRoomBooking(int $index, RoomBooking $booking): void
    {
        $this->roomBookings->offsetSet($index, $booking);
    }

    public function deleteRoomBooking(int $index): void
    {
        $this->roomBookings->offsetUnset($index);
    }

    public function cancelConditions(): CancelConditions
    {
        return $this->cancelConditions;
    }

    public function note(): ?string
    {
        return $this->note;
    }

    public function setNote(string|null $note): void
    {
        $this->note = $note;
    }
}
