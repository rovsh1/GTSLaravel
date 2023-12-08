<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\ServiceBooking\CarBid;

use Module\Booking\Moderation\Application\Dto\CarBidDataDto;
use Module\Booking\Moderation\Application\Service\CarBidFactory;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\Event\TransferBooking\CarBidAdded;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Add implements UseCaseInterface
{
    public function __construct(
        private readonly CarBidFactory $carBidFactory,
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(int $bookingId, CarBidDataDto $carData): void
    {
        $booking = $this->bookingUnitOfWork->findOrFail(new BookingId($bookingId));

        $this->carBidFactory->fromRequest($carData);

        $this->bookingUnitOfWork->touch($booking->id());
        $this->bookingUnitOfWork->commiting(function () use ($booking) {
            $carBid = $this->carBidFactory->create($booking->id());
            $this->eventDispatcher->dispatch(new CarBidAdded($carBid));
        });
        $this->bookingUnitOfWork->commit();
    }
}
