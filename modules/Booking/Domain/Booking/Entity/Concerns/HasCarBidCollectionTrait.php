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
                $newCarBids[] = new CarBid(
                    $bid->id(),
                    $carBid->carId(),
                    $carBid->carsCount(),
                    $carBid->passengersCount(),
                    $carBid->baggageCount(),
                    $carBid->babyCount()
                );
            }
            $newCarBids[] = $bid;
        }
        $this->carBids = new CarBidCollection($newCarBids);
    }
}
