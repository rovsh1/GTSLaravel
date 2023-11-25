<?php

namespace Sdk\Booking\Entity\BookingDetails\Concerns;

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
    }

    public function removeCarBid(string $carBidId): void
    {
        $newCarBids = [];
        foreach ($this->carBids as $bid) {
            if ($bid->id() === $carBidId) {
                continue;
            }
            $newCarBids[] = $bid;
        }
        $this->carBids = new CarBidCollection($newCarBids);
    }

    public function replaceCarBid(string $carBidId, CarBid $carBid): void
    {
        $newCarBids = [];
        foreach ($this->carBids as $bid) {
            if ($bid->id() === $carBidId) {
                $newCarBids[] = $carBid;
                continue;
            }
            $newCarBids[] = $bid;
        }
        $this->carBids = new CarBidCollection($newCarBids);
    }
}
