<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Entity;

use Module\Booking\Shared\Domain\Booking\Entity\Concerns\HasGuestIdCollectionTrait;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationDetails;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomInfo;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomPrices;
use Module\Booking\Shared\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Shared\Contracts\Support\SerializableInterface;

final class HotelAccommodation implements SerializableInterface
{
    use HasGuestIdCollectionTrait;

    public function __construct(
        private readonly AccommodationId $id,
        private readonly BookingId $bookingId,
        private readonly RoomInfo $roomInfo,
        private GuestIdCollection $guestIds,
        private AccommodationDetails $details,
        private RoomPrices $prices,
    ) {
    }

    public function id(): AccommodationId
    {
        return $this->id;
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function roomInfo(): RoomInfo
    {
        return $this->roomInfo;
    }

    public function details(): AccommodationDetails
    {
        return $this->details;
    }

    public function updateDetails(AccommodationDetails $details): void
    {
        $this->details = $details;
    }

    public function prices(): RoomPrices
    {
        return $this->prices;
    }

    public function updatePrices(RoomPrices $prices): void
    {
        $this->prices = $prices;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->value(),
            'bookingId' => $this->bookingId->value(),
            'roomInfo' => $this->roomInfo->serialize(),
            'guestIds' => $this->guestIds->serialize(),
            'details' => $this->details->serialize(),
            'prices' => $this->prices->serialize(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new HotelAccommodation(
            new AccommodationId($payload['id']),
            new BookingId($payload['bookingId']),
            RoomInfo::deserialize($payload['roomInfo']),
            GuestIdCollection::deserialize($payload['guestIds']),
            AccommodationDetails::deserialize($payload['details']),
            RoomPrices::deserialize($payload['prices']),
        );
    }
}
