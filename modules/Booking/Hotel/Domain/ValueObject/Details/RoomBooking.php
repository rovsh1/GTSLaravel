<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\ValueObject\Details;

use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\Guest;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomBookingStatusEnum;
use Module\Booking\Hotel\Domain\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Shared\Domain\ValueObject\SerializableDataInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class RoomBooking implements ValueObjectInterface, SerializableDataInterface
{
    public function __construct(
        private RoomBookingStatusEnum $status,
        private RoomInfo $roomInfo,
        private GuestCollection $guests,
        private RoomBookingDetails $details
    ) {}

    public function status(): RoomBookingStatusEnum
    {
        return $this->status;
    }

    public function guests(): GuestCollection
    {
        return $this->guests;
    }

    public function roomInfo(): RoomInfo
    {
        return $this->roomInfo;
    }

    public function details(): RoomBookingDetails
    {
        return $this->details;
    }

    public function addGuest(Guest $guest): void
    {
        $this->guests->add($guest);
    }

    public function updateGuest(int $index, Guest $guest): void
    {
        $this->guests->offsetSet($index, $guest);
    }

    public function toData(): array
    {
        return [
            'status' => $this->status->value,
            'guests' => $this->guests->toData(),
            'roomInfo' => $this->roomInfo->toData(),
            'details' => $this->details->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            RoomBookingStatusEnum::from($data['status']),
            RoomInfo::fromData($data['roomInfo']),
            GuestCollection::fromData($data['guests']),
            RoomBookingDetails::fromData($data['details'])
        );
    }
}
