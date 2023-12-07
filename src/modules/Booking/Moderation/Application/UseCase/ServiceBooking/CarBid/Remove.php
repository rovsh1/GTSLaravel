<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\ServiceBooking\CarBid;

use Module\Booking\Moderation\Application\Exception\NotFoundServiceCancelConditionsException;
use Module\Booking\Moderation\Domain\Booking\Exception\NotFoundServiceCancelConditions;
use Module\Booking\Shared\Domain\Booking\DbContext\CarBidDbContextInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\Event\TransferBooking\CarBidRemoved;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\CarBidId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Remove implements UseCaseInterface
{
    public function __construct(
        private readonly CarBidDbContextInterface $carBidDbContext,
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(int $bookingId, int $carBidId): void
    {
        $booking = $this->bookingUnitOfWork->findOrFail(new BookingId($bookingId));
        $currentCarBid = $this->carBidDbContext->findOrFail(new CarBidId($carBidId));

        $this->bookingUnitOfWork->touch($booking->id());

        $this->bookingUnitOfWork->commiting(function () use ($currentCarBid) {
            $this->carBidDbContext->delete($currentCarBid->id());
            $details = $this->bookingUnitOfWork->getDetails($currentCarBid->bookingId());
            $this->eventDispatcher->dispatch(new CarBidRemoved($details, $currentCarBid));
        });

        try {
            //@todo проверить, что корректно отрабатывает
            $this->bookingUnitOfWork->commit();
        } catch (NotFoundServiceCancelConditions $e) {
            throw new NotFoundServiceCancelConditionsException($e);
        }
    }
}
