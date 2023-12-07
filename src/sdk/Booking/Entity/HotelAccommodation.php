<?php

declare(strict_types=1);

namespace Sdk\Booking\Entity;

use Sdk\Booking\Contracts\Entity\BookingPartInterface;
use Sdk\Booking\Event\HotelBooking\AccommodationDetailsEdited;
use Sdk\Booking\Event\HotelBooking\GuestBinded;
use Sdk\Booking\Event\HotelBooking\GuestUnbinded;
use Sdk\Booking\Exception\GuestAlreadyExists;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Booking\ValueObject\GuestIdCollection;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationDetails;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationId;
use Sdk\Booking\ValueObject\HotelBooking\RoomInfo;
use Sdk\Booking\ValueObject\HotelBooking\RoomPrices;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

final class HotelAccommodation extends AbstractAggregateRoot implements BookingPartInterface
{
    public function __construct(
        private readonly AccommodationId $id,
        private readonly BookingId $bookingId,
        private readonly RoomInfo $roomInfo,
        private GuestIdCollection $guestIds,
        private AccommodationDetails $details,
        private RoomPrices $prices,
    ) {}

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
        $this->pushEvent(new AccommodationDetailsEdited($this, $this->details));
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

    public function guestIds(): GuestIdCollection
    {
        return $this->guestIds;
    }

    public function addGuest(GuestId $id): void
    {
        if ($this->guestIds->has($id)) {
            throw new GuestAlreadyExists('Guest already exists');
        }
        $this->guestIds = new GuestIdCollection([...$this->guestIds->all(), $id]);
        $this->pushEvent(new GuestBinded($this, $id));
    }

    public function removeGuest(GuestId $guestId): void
    {
        if (!$this->guestIds->has($guestId)) {
            throw new GuestAlreadyExists('Guest not found');
        }
        $this->guestIds = new GuestIdCollection(
            array_filter($this->guestIds->all(), fn($id) => !$guestId->isEqual($id))
        );
        $this->pushEvent(new GuestUnbinded($this, $guestId));
    }

    public function guestsCount(): int
    {
        return count($this->guestIds);
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