<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\ServiceBooking\CarBid;

use Module\Booking\Moderation\Application\Service\CarBidUpdateHelper;
use Module\Booking\Moderation\Domain\Booking\Event\CarBidRemoved;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Remove implements UseCaseInterface
{
    public function __construct(
        private readonly CarBidUpdateHelper $carBidUpdateHelper,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(int $bookingId, string $carBidId): void
    {
        $this->carBidUpdateHelper->boot($bookingId);
        $booking = $this->carBidUpdateHelper->booking();
        $details = $this->carBidUpdateHelper->details();
        $carBid = $details->carBids()->find($carBidId);
        $details->removeCarBid($carBidId);
        $this->carBidUpdateHelper->store();
        $this->eventDispatcher->dispatch(new CarBidRemoved($booking, $carBid));
    }
}
