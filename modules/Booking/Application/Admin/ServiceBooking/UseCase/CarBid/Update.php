<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\UseCase\CarBid;

use Module\Booking\Application\Admin\ServiceBooking\Dto\CarBidDataDto;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\CarBid;
use Module\Booking\Domain\Booking\ValueObject\CarId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Update extends AbstractCarBidUseCase implements UseCaseInterface
{
    public function execute(int $bookingId, string $carBidId, CarBidDataDto $carData): void
    {
        $details = $this->getBookingDetails(new BookingId($bookingId));
        $carBid = new CarBid(
            $carBidId,
            new CarId($carData->carId),
            $carData->carsCount,
            $carData->passengersCount,
            $carData->baggageCount,
            $carData->babyCount
        );
        $details->replaceCarBid($carBidId, $carBid);
        $this->storeDetails($details);
    }
}
