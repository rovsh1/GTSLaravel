<?php

namespace Module\Booking\Domain\Booking\Entity\Concerns;

use Module\Booking\Domain\Booking\Exception\CarAlreadyExists;
use Module\Booking\Domain\Booking\ValueObject\CarBid;
use Module\Booking\Domain\Booking\ValueObject\CarBidCollection;

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

    public function removeCarBid(CarBid $carBid): void
    {
        $newCarBids = [];
        foreach ($this->carBids as $bid) {
            if ($bid->carId()->isEqual($carBid->carId())) {
                continue;
            }
            $newCarBids[] = $bid;
        }
        $this->carBids = new CarBidCollection($newCarBids);
    }
}
