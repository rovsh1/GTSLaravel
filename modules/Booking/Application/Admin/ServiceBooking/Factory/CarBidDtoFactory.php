<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Factory;

use Module\Booking\Application\Admin\ServiceBooking\Dto\CarBidDto;
use Module\Booking\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Domain\Booking\ValueObject\CarBid;
use Module\Booking\Domain\Booking\ValueObject\CarBidCollection;

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
        $cars = $this->supplierAdapter->getCars($supplierId);
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
