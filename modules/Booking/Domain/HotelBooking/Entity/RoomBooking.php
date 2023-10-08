<?php

declare(strict_types=1);

namespace Module\Booking\Domain\HotelBooking\Entity;

use Module\Booking\Domain\HotelBooking\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\RoomBooking\RoomBookingId;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Booking\Domain\HotelBooking\ValueObject\RoomPrice;
use Module\Booking\Domain\Shared\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Booking\Domain\Shared\ValueObject\OrderId;
use Module\Shared\Domain\Entity\EntityInterface;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class RoomBooking extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly RoomBookingId $id,
        private readonly BookingId $bookingId,
        private readonly OrderId $orderId,
        private RoomInfo $roomInfo,
        private GuestIdCollection $guestsIds,
        private RoomBookingDetails $details,
        private readonly RoomPrice $price,
    ) {
    }

    public function id(): RoomBookingId
    {
        return $this->id;
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function orderId(): OrderId
    {
        return $this->orderId;
    }

    public function guestIds(): GuestIdCollection
    {
        return $this->guestsIds;
    }

    public function guestsCount(): int
    {
        return count($this->guestsIds);
    }

    public function roomInfo(): RoomInfo
    {
        return $this->roomInfo;
    }

    public function details(): RoomBookingDetails
    {
        return $this->details;
    }

    public function setDetails(RoomBookingDetails $details): void
    {
        $this->details = $details;
    }

    public function price(): RoomPrice
    {
        return $this->price;
    }

    public function toData(): array
    {
        return [
            'id' => $this->id->value(),
            'orderId' => $this->orderId->value(),
            'bookingId' => $this->bookingId->value(),
            'roomInfo' => $this->roomInfo->toData(),
            'details' => $this->details->toData(),
            'price' => $this->price->toData()
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            id: new RoomBookingId($data['id']),
            bookingId: new BookingId($data['bookingId']),
            orderId: new OrderId($data['orderId']),
            roomInfo: RoomInfo::fromData($data['roomInfo']),
            guestsIds: GuestIdCollection::fromData($data['guestIds']),
            details: RoomBookingDetails::fromData($data['details']),
            price: RoomPrice::fromData($data['price'])
        );
    }
}
