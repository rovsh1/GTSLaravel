<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\ServiceBooking\CarBid;

use Module\Booking\Moderation\Application\Dto\CarBidDataDto;
use Module\Booking\Moderation\Application\Service\CarBidUpdateHelper;
use Module\Booking\Moderation\Domain\Booking\Event\CarBidAdded;
use Sdk\Booking\ValueObject\CarBid;
use Sdk\Booking\ValueObject\CarId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Add implements UseCaseInterface
{
    public function __construct(
        private readonly CarBidUpdateHelper $carBidUpdateHelper,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(int $bookingId, CarBidDataDto $carData): void
    {
        $this->carBidUpdateHelper->boot($bookingId, $carData->carId);

        $carBid = CarBid::create(
            new CarId($carData->carId),
            $carData->carsCount,
            $carData->passengersCount,
            $carData->baggageCount,
            $carData->babyCount,
            $this->carBidUpdateHelper->prices()
        );
        $this->carBidUpdateHelper->details()->addCarBid($carBid);
        $this->carBidUpdateHelper->store();
        $this->eventDispatcher->dispatch(new CarBidAdded($this->carBidUpdateHelper->booking(), $carBid));
    }
}
