<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\Booking\UseCase;

use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class DeleteBooking implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function execute(int $id): void
    {
        $booking = $this->repository->findOrFail(new BookingId($id));
        $booking->delete();
        $this->repository->delete($booking);
        $this->eventDispatcher->dispatch(...$booking->pullEvents());
    }
}