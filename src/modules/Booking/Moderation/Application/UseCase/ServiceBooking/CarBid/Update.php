<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\ServiceBooking\CarBid;

use Module\Booking\Moderation\Application\Dto\CarBidDataDto;
use Module\Booking\Moderation\Application\Service\CarBidUpdateHelper;
use Module\Booking\Moderation\Domain\Booking\Event\CarBidUpdated;
use Sdk\Booking\ValueObject\CarBid;
use Sdk\Booking\ValueObject\CarId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Update implements UseCaseInterface
{
    public function __construct(
        private readonly CarBidUpdateHelper $carBidUpdateHelper,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(int $bookingId, string $carBidId, CarBidDataDto $carData): void
    {
        $this->carBidUpdateHelper->boot($bookingId, $carData->carId);
        $details = $this->carBidUpdateHelper->details();
        $carBid = new CarBid(
            $carBidId,
            new CarId($carData->carId),
            $carData->carsCount,
            $carData->passengersCount,
            $carData->baggageCount,
            $carData->babyCount,
            $this->carBidUpdateHelper->prices()
        );

        $carBidBefore = $details->carBids()->find($carBid->carId());
        $details->replaceCarBid($carBidId, $carBid);
        $this->carBidUpdateHelper->store();

        $this->eventDispatcher->dispatch(
            new CarBidUpdated($this->carBidUpdateHelper->booking(), $carBidBefore, $carBid)
        );
    }
}
