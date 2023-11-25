<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Factory;

use Module\Booking\Moderation\Application\Dto\ServiceBooking\CarBidDto;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Sdk\Booking\ValueObject\CarBid;
use Sdk\Booking\ValueObject\CarBidCollection;

class CarBidDtoFactory
{
    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter
    ) {}

    /**
     * @param CarBidCollection $carBids
     * @return array<int, CarBidDto>
     */
    public function build(int $supplierId, CarBidCollection $carBids): array
    {
        $cars = $this->supplierAdapter->getSupplierCars($supplierId);
        $carsIndexedById = collect($cars)->keyBy('id');

        return $carBids->map(fn(CarBid $carBid) => new CarBidDto(
            id: $carBid->id(),
            carInfo: $carsIndexedById[$carBid->carId()->value()],
            carsCount: $carBid->carsCount(),
            passengersCount: $carBid->passengersCount(),
            baggageCount: $carBid->baggageCount(),
            babyCount: $carBid->babyCount()
        ));
    }
}
