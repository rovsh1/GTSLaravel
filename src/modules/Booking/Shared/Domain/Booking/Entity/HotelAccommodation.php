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
use Module\Shared\Contracts\Support\SerializableDataInterface;

final class HotelAccommodation implements SerializableDataInterface
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

    public function toData(): array
    {
        return [
            'id' => $this->id->value(),
            'bookingId' => $this->bookingId->value(),
            'roomInfo' => $this->roomInfo->toData(),
            'guestIds' => $this->guestIds->toData(),
            'details' => $this->details->toData(),
            'prices' => $this->prices->toData(),
        ];
    }

    public static function fromData(array $data): static
    {
        return new HotelAccommodation(
            new AccommodationId($data['id']),
            new BookingId($data['bookingId']),
            RoomInfo::fromData($data['roomInfo']),
            GuestIdCollection::fromData($data['guestIds']),
            AccommodationDetails::fromData($data['details']),
            RoomPrices::fromData($data['prices']),
        );
    }
}
