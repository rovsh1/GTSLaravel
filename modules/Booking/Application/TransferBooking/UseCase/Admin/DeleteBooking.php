<?php

declare(strict_types=1);

namespace Module\Booking\Application\TransferBooking\UseCase\Admin;

use Module\Booking\Domain\Shared\ValueObject\BookingId;
use Module\Booking\Domain\TransferBooking\Repository\BookingRepositoryInterface;
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