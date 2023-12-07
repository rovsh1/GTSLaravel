<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Factory;

use Module\Booking\Moderation\Application\Dto\ServiceBooking\CarBidDto;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\DbContext\CarBidDbContextInterface;
use Module\Supplier\Moderation\Application\Dto\CarDto;
use Sdk\Booking\Contracts\Entity\TransferDetailsInterface;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\ValueObject\BookingId;

class CarBidDtoFactory
{
    /**
     * @var array<int, array<int, CarDto>> $supplierCarsIndexedById
     */
    private array $supplierCarsIndexedById = [];

    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly CarBidDbContextInterface $carBidDbContext,
    ) {}

    /**
     * @param BookingId $bookingId
     * @param int $supplierId
     * @return array<int, CarBidDto>
     */
    public function build(TransferDetailsInterface $details): array
    {
        $carBids = $this->carBidDbContext->getByBookingId($details->bookingId());

        return $carBids->map(fn(CarBid $carBid) => new CarBidDto(
            id: $carBid->id()->value(),
            carInfo: $this->getSupplierCar($details->serviceInfo()->supplierId(), $carBid->carId()->value()),
            carsCount: $carBid->carsCount(),
            passengersCount: $carBid->passengersCount(),
            baggageCount: $carBid->baggageCount(),
            babyCount: $carBid->babyCount(),
            guestIds: $carBid->guestIds()->serialize()
        ));
    }

    private function getSupplierCar(int $supplierId, int $carId): CarDto
    {
        $supplierCars = $this->supplierCarsIndexedById[$supplierId] ?? null;
        if ($supplierCars === null) {
            $cars = $this->supplierAdapter->getSupplierCars($supplierId);
            $supplierCars = collect($cars)->keyBy('id')->all();
            $this->supplierCarsIndexedById[$supplierId] = $supplierCars;
        }

        return $supplierCars[$carId];
    }
}
