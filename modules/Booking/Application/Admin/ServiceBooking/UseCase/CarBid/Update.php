<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\UseCase\CarBid;

use Module\Booking\Application\Admin\ServiceBooking\Request\CarBidDataDto;
use Module\Booking\Domain\Booking\Factory\DetailsRepositoryFactory;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\CarBid;
use Module\Booking\Domain\Booking\ValueObject\CarId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Update implements UseCaseInterface
{
    public function __construct(
        private readonly DetailsRepositoryFactory $detailsRepositoryFactory,
    ) {}

    public function execute(int $bookingId, string $carBidId, CarBidDataDto $carData): void
    {
        $id = new BookingId($bookingId);
        $repository = $this->detailsRepositoryFactory->buildByBookingId($id);
        $details = $repository->find($id);
        $carBid = new CarBid(
            $carBidId,
            new CarId($carData->carId),
            $carData->carsCount,
            $carData->passengersCount,
            $carData->baggageCount,
            $carData->babyCount
        );
        $details->replaceCarBid($carBidId, $carBid);
        $repository->store($details);
    }
}
