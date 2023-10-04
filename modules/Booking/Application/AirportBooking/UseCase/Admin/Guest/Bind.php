<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\UseCase\Admin\Guest;

use Module\Booking\Airport\Domain\Booking\Event\GuestBinded;
use Module\Booking\Airport\Domain\Booking\Repository\BookingGuestRepositoryInterface;
use Module\Booking\Airport\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Order\ValueObject\GuestId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class Bind implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly BookingGuestRepositoryInterface $bookingGuestRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(int $bookingId, int $guestId): void
    {
        $booking = $this->bookingRepository->find($bookingId);
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }
        $newGuestId = new GuestId($guestId);
        $this->bookingGuestRepository->bind($booking->id(), $newGuestId);
        $this->eventDispatcher->dispatch(
            new GuestBinded(
                $booking->id(),
                $booking->orderId(),
                $newGuestId,
            )
        );
    }
}
