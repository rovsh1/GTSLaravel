<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\DbContext;

use Module\Booking\Shared\Domain\Booking\DbContext\CarBidDbContextInterface;
use Module\Booking\Shared\Infrastructure\Mapper\CarBidMapper;
use Module\Booking\Shared\Infrastructure\Models\CarBid as Model;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\CarId;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBid\CarBidPrices;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Booking\ValueObject\CarBidId;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class CarBidDbContext implements CarBidDbContextInterface
{
    public function __construct(
        private readonly CarBidMapper $mapper,
    ) {}

    public function find(CarBidId $id): ?CarBid
    {
        $model = Model::find($id->value());
        if (!$model) {
            return null;
        }

        return $this->mapper->fromModel($model);
    }

    public function findOrFail(CarBidId $id): CarBid
    {
        return $this->find($id) ?? throw new EntityNotFoundException("CarBid[{$id->value()}] not found");
    }

    public function getByBookingId(BookingId $bookingId): CarBidCollection
    {
        $models = Model::whereBookingId($bookingId->value())->get();

        return $this->mapper->collectionFromModel($models);
    }

    public function store(CarBid $carBid): void
    {
        $model = Model::find($carBid->id()->value());

        $model->update([
            'supplier_car_id' => $carBid->carId()->value(),
            'cars_count' => $carBid->carsCount(),
            'passengers_count' => $carBid->passengersCount(),
            'baggage_count' => $carBid->baggageCount(),
            'baby_count' => $carBid->babyCount(),
            'prices' => $carBid->prices()->serialize(),
            'guestIds' => $carBid->guestIds()->serialize(),
        ]);
    }

    public function delete(CarBidId $id): void
    {
        Model::whereId($id->value())->delete();
    }

    public function create(
        BookingId $bookingId,
        CarId $carId,
        int $carsCount,
        int $passengersCount,
        int $baggageCount,
        int $babyCount,
        CarBidPrices $prices
    ): CarBid {
        $model = Model::create([
            'booking_id' => $bookingId->value(),
            'supplier_car_id' => $carId->value(),
            'cars_count' => $carsCount,
            'passengers_count' => $passengersCount,
            'baggage_count' => $baggageCount,
            'baby_count' => $babyCount,
            'prices' => $prices->serialize(),
        ]);

        return $this->mapper->fromModel($model);
    }
}
