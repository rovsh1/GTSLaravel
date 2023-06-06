<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\Entity;

use Module\Booking\Common\Domain\Entity\Details\BookingDetailsInterface;
use Module\Booking\Hotel\Domain\ValueObject\Details\AdditionalInfo;
use Module\Booking\Hotel\Domain\ValueObject\Details\BookingPeriod;
use Module\Booking\Hotel\Domain\ValueObject\Details\CancelConditions;
use Module\Booking\Hotel\Domain\ValueObject\Details\HotelInfo;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBookingCollection;
use Module\Shared\Domain\ValueObject\Id;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;

final class Details implements BookingDetailsInterface, SerializableDataInterface
{
    public function __construct(
        private readonly Id $id,
        private HotelInfo $hotelInfo,
        private BookingPeriod $period,
        private ?AdditionalInfo $additionalInfo,
        private RoomBookingCollection $roomBookings,
        private CancelConditions $cancelConditions
    ) {}

    public function id(): Id
    {
        return $this->id;
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

    public function toData(): array
    {
        return [
            'id' => $this->id->value(),
            'hotelInfo' => $this->hotelInfo->toData(),
            'additionalInfo' => $this->additionalInfo?->toData(),
            'period' => $this->period->toData(),
            'roomBookings' => $this->roomBookings->toData(),
            'cancelConditions' => $this->cancelConditions->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        $additionalInfo = $data['additionalInfo'] ?? null;

        return new static(
            new Id($data['id']),
            HotelInfo::fromData($data['hotelInfo']),
            BookingPeriod::fromData($data['period']),
            $additionalInfo !== null ? AdditionalInfo::fromData($data['additionalInfo']) : null,
            RoomBookingCollection::fromData($data['roomBookings']),
            CancelConditions::fromData($data['cancelConditions'])
        );
    }
}
