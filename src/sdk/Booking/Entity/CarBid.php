<?php

namespace Sdk\Booking\Entity;

use Sdk\Booking\Contracts\Entity\BookingPartInterface;
use Sdk\Booking\Entity\Details\Concerns\HasGuestIdCollectionTrait;
use Sdk\Booking\Event\TransferBooking\CarBidDetailsEdited;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBid\CarBidDetails;
use Sdk\Booking\ValueObject\CarBid\CarBidPrices;
use Sdk\Booking\ValueObject\CarBidId;
use Sdk\Booking\ValueObject\CarId;
use Sdk\Booking\ValueObject\GuestIdCollection;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

final class CarBid extends AbstractAggregateRoot implements BookingPartInterface
{
    use HasGuestIdCollectionTrait;

    public function __construct(
        private readonly CarBidId $id,
        private readonly BookingId $bookingId,
        private CarId $carId,
        private CarBidDetails $details,
        private CarBidPrices $prices,
        private GuestIdCollection $guestIds,
    ) {}

    public function id(): CarBidId
    {
        return $this->id;
    }

    public function bookingId(): BookingId
    {
        return $this->bookingId;
    }

    public function carId(): CarId
    {
        return $this->carId;
    }

    public function details(): CarBidDetails
    {
        return $this->details;
    }

    public function updateDetails(CarBidDetails $details): void
    {
        $this->pushEvent(new CarBidDetailsEdited($this, $this->details));
        $this->details = $details;
    }

    public function prices(): CarBidPrices
    {
        return $this->prices;
    }

    public function supplierPriceValue(): float
    {
        return $this->prices->supplierPrice()->valuePerCar() * $this->details->carsCount();
    }

    public function clientPriceValue(): float
    {
        return $this->prices->clientPrice()->valuePerCar() * $this->details->carsCount();
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'bookingId' => $this->bookingId,
            'carId' => $this->carId->value(),
            'details' => $this->details->serialize(),
            'prices' => $this->prices->serialize(),
            'guestIds' => $this->guestIds->serialize(),
        ];
    }

    public static function deserialize(array $payload): static
    {
        return new static(
            new CarBidId($payload['id']),
            new BookingId($payload['booking_id']),
            new CarId($payload['carId']),
            CarBidDetails::deserialize($payload['details']),
            CarBidPrices::deserialize($payload['prices']),
            GuestIdCollection::deserialize($payload['guestIds']),
        );
    }
}
