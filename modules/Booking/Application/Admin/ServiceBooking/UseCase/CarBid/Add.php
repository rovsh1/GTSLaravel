<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\UseCase\CarBid;

use Module\Booking\Application\Admin\ServiceBooking\Request\CarBidDataDto;
use Module\Booking\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Domain\Booking\Factory\DetailsRepositoryFactory;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\CarBid;
use Module\Booking\Domain\Booking\ValueObject\CarId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class Add implements UseCaseInterface
{
    public function __construct(
        private readonly DetailsRepositoryFactory $detailsRepositoryFactory,
        private readonly SupplierAdapterInterface $supplierAdapter,
    ) {}

    public function execute(int $bookingId, CarBidDataDto $carData): void
    {
        $car = $this->supplierAdapter->findCar($carData->carId);
        if ($car === null) {
            throw new EntityNotFoundException('Car not found');
        }
        $id = new BookingId($bookingId);
        $repository = $this->detailsRepositoryFactory->buildByBookingId($id);
        $details = $repository->find($id);
        $carBid = CarBid::create(
            new CarId($carData->carId),
            $carData->carsCount,
            $carData->passengersCount,
            $carData->baggageCount,
            $carData->babyCount
        );
        $details->addCarBid($carBid);
        $repository->store($details);
    }
}
