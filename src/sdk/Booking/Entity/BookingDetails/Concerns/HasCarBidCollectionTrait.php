<?php

namespace Sdk\Booking\Entity\BookingDetails\Concerns;

use Sdk\Booking\Event\ServiceBooking\CarBidAdded;
use Sdk\Booking\Event\ServiceBooking\CarBidRemoved;
use Sdk\Booking\Event\ServiceBooking\CarBidUpdated;
use Sdk\Booking\Exception\CarAlreadyExists;
use Sdk\Booking\ValueObject\CarBid;
use Sdk\Booking\ValueObject\CarBidCollection;

trait HasCarBidCollectionTrait
{
    public function carBids(): CarBidCollection
    {
        return $this->carBids;
    }

    public function addCarBid(CarBid $carBid): void
    {
        if ($this->carBids->hasCar($carBid->carId())) {
            throw new CarAlreadyExists('Car already exists');
        }
        $this->carBids = new CarBidCollection([
            ...$this->carBids->all(),
            $carBid
        ]);

        $this->pushEvent(new CarBidAdded($this, $carBid));
    }

    public function removeCarBid(string $carBidId): void
    {
        $removingCarBid = null;
        $newCarBids = [];
        foreach ($this->carBids as $bid) {
            if ($bid->id() === $carBidId) {
                $removingCarBid = $bid;
            } else {
                $newCarBids[] = $bid;
            }
        }
        $this->carBids = new CarBidCollection($newCarBids);
        $this->pushEvent(new CarBidRemoved($this, $removingCarBid));
    }

    public function replaceCarBid(string $carBidId, CarBid $carBid): void
    {
        $originalCarBid = null;
        $newCarBids = [];
        foreach ($this->carBids as $bid) {
            if ($bid->id() === $carBidId) {
                $originalCarBid = $bid;
                $newCarBids[] = $carBid;
            } else {
                $newCarBids[] = $bid;
            }
        }
        $this->carBids = new CarBidCollection($newCarBids);
        $this->pushEvent(new CarBidUpdated($this, $originalCarBid, $carBid));
    }
}
