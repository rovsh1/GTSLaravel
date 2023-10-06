<?php

namespace Module\Booking\Domain\ServiceBooking\Support\Concerns;

use Module\Booking\Domain\ServiceBooking\ValueObject\CarBid;
use Module\Booking\Domain\ServiceBooking\ValueObject\CarBidCollection;

trait HasCarBidCollectionTrait
{
    public function carBids(): CarBidCollection
    {
        return $this->carBids;
    }

    public function addCarBid(CarBid $carBid): void
    {
        $this->carBids = $carBids;
    }
}