<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase;

use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateNote implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function execute(int $id, ?string $note): void
    {
        $booking = $this->bookingRepository->findOrFail(new BookingId($id));
        $booking->setNote($note);
        $this->bookingRepository->store($booking);
        $this->eventDispatcher->dispatch(...$booking->pullEvents());
    }
}
