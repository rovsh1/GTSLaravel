<?php

declare(strict_types=1);

namespace Module\Booking\Deprecated\HotelBooking\Entity;

use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingDetails;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomInfo;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomPrices;
use Module\Booking\Domain\Order\ValueObject\OrderId;
use Module\Booking\Domain\Shared\ValueObject\GuestIdCollection;
use Module\Shared\Contracts\Domain\EntityInterface;
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
        private RoomPrices $price,
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

    public function price(): RoomPrices
    {
        return $this->price;
    }

    public function updatePrice(RoomPrices $price): void
    {
        $this->price = $price;
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
            price: RoomPrices::fromData($data['price'])
        );
    }
}
