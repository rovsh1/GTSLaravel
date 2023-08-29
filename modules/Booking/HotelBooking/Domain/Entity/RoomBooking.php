<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Entity;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\RoomPriceEditor;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingId;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingStatusEnum;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Booking\HotelBooking\Domain\ValueObject\RoomPrice;
use Module\Booking\Order\Domain\ValueObject\GuestIdsCollection;
use Module\Shared\Domain\Entity\EntityInterface;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class RoomBooking extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly RoomBookingId $id,
        private readonly BookingId $bookingId,
        private readonly OrderId $orderId,
        private RoomBookingStatusEnum $status,
        private RoomInfo $roomInfo,
        private GuestIdsCollection $guestsIds,
        private RoomBookingDetails $details,
        private RoomPrice $price,
    ) {}

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

    public function status(): RoomBookingStatusEnum
    {
        return $this->status;
    }

    public function guestIds(): GuestIdsCollection
    {
        return $this->guestsIds;
    }

    public function roomInfo(): RoomInfo
    {
        return $this->roomInfo;
    }

    public function details(): RoomBookingDetails
    {
        return $this->details;
    }

    public function price(): RoomPrice
    {
        return $this->price;
    }

    public function recalculatePrices(RoomPriceEditor $editor): void
    {
        $this->price = $editor->recalculatePrices($this);
    }

    public function setHoDayPrice(float $price, RoomPriceEditor $editor): void
    {
        $this->price = $editor->setManuallyHoPrice($this, $price);
    }

    public function setBoDayPrice(float $price, RoomPriceEditor $editor): void
    {
        $this->price = $editor->setManuallyBoPrice($this, $price);
    }

    public function setCalculatedPrices(RoomPriceEditor $editor): void
    {
        $this->price = $editor->setCalculatedPrices($this);
    }

    public function setCalculatedBoPrice(RoomPriceEditor $editor): void
    {
        $this->price = $editor->setCalculatedBoPrice($this);
    }

    public function setCalculatedHoPrice(RoomPriceEditor $editor): void
    {
        $this->price = $editor->setCalculatedHoPrice($this);
    }

    public function toData(): array
    {
        return [
            'id' => $this->id->value(),
            'orderId' => $this->orderId->value(),
            'bookingId' => $this->bookingId->value(),
            'status' => $this->status->value,
            'roomInfo' => $this->roomInfo->toData(),
            'details' => $this->details->toData(),
            'price' => $this->price->toData()
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            id: new RoomBookingId($data['id']),
            orderId: new OrderId($data['orderId']),
            bookingId: new BookingId($data['bookingId']),
            status: RoomBookingStatusEnum::from($data['status']),
            roomInfo: RoomInfo::fromData($data['roomInfo']),
            guestsIds: GuestIdsCollection::fromData($data['guestIds']),
            details: RoomBookingDetails::fromData($data['details']),
            price: RoomPrice::fromData($data['price'])
        );
    }
}
