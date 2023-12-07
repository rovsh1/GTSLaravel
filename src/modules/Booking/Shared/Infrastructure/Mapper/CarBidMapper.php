<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Mapper;

use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Shared\Infrastructure\Models\CarBid as Model;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBid\CarBidPrices;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\CarBidId;
use Sdk\Booking\ValueObject\CarId;
use Sdk\Booking\ValueObject\GuestIdCollection;

class CarBidMapper
{
    public function fromModel(Model $model): CarBid
    {
        return new CarBid(
            new CarBidId($model->id),
            new BookingId($model->booking_id),
            new CarId($model->supplier_car_id),
            $model->cars_count,
            $model->passengers_count,
            $model->baggage_count,
            $model->baby_count,
            CarBidPrices::deserialize($model->prices),
            GuestIdCollection::deserialize($model->guestIds)
        );
    }

    public function collectionFromModel(Collection $carBids): CarBidCollection
    {
        return new CarBidCollection($carBids->map(fn(Model $carBid) => $this->fromModel($carBid))->all());
    }
}
