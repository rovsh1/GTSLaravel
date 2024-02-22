<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase;

use Module\Booking\Shared\Domain\Booking\DbContext\BookingDbContextInterface;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class DeleteBooking implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingDbContextInterface $bookingDbContext,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(int $id): void
    {
        $booking = $this->repository->findOrFail(new BookingId($id));
        $booking->delete();
        $this->bookingDbContext->delete($booking);
        $this->eventDispatcher->dispatch(...$booking->pullEvents());
    }
}
