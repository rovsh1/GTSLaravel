<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Entity;

use Module\Booking\HotelBooking\Domain\Event\GuestAdded;
use Module\Booking\HotelBooking\Domain\Event\GuestDeleted;
use Module\Booking\HotelBooking\Domain\Event\GuestEdited;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\RoomPriceEditor;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\GuestCollection;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\Guest;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingDetails;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingStatusEnum;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomInfo;
use Module\Booking\HotelBooking\Domain\ValueObject\RoomPrice;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Id;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class RoomBooking extends AbstractAggregateRoot implements EntityInterface
{
    public function __construct(
        private readonly Id $id,
        private readonly Id $bookingId,
        private readonly Id $orderId,
        private RoomBookingStatusEnum $status,
        private RoomInfo $roomInfo,
        //@todo возможно придется переносить гостей в отдельную таблицу с привязкой к заказу (для составных броней)
        private GuestCollection $guests,
        private RoomBookingDetails $details,
        private RoomPrice $price,
    ) {}

    public function id(): Id
    {
        return $this->id;
    }

    public function bookingId(): Id
    {
        return $this->bookingId;
    }

    public function orderId(): Id
    {
        return $this->orderId;
    }

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

    public function price(): RoomPrice
    {
        return $this->price;
    }

    public function addGuest(Guest $guest): void
    {
        $this->guests->add($guest);
        $this->pushEvent(
            new GuestAdded(
                $this,
                $guest
            )
        );
    }

    public function updateGuest(int $index, Guest $guest): void
    {
        $this->guests->offsetSet($index, $guest);
        $this->pushEvent(
            new GuestEdited(
                $this,
                $guest,
                '',
                '',
                ''
            )
        );
    }

    public function deleteGuest(int $index): void
    {
        $this->guests->offsetUnset($index);
        //@todo кинуть ивент
        $this->pushEvent(
            new GuestDeleted(
                $this,
                $index,
                ''//@todo надо ли?
            )
        );
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
            'guests' => $this->guests->toData(),
            'roomInfo' => $this->roomInfo->toData(),
            'details' => $this->details->toData(),
            'price' => $this->price->toData()
        ];
    }

    public static function fromData(array $data): static
    {
        return new static(
            id: new Id($data['id']),
            orderId: new Id($data['orderId']),
            bookingId: new Id($data['bookingId']),
            status: RoomBookingStatusEnum::from($data['status']),
            roomInfo: RoomInfo::fromData($data['roomInfo']),
            guests: GuestCollection::fromData($data['guests']),
            details: RoomBookingDetails::fromData($data['details']),
            price: RoomPrice::fromData($data['price'])
        );
    }
}
