<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking\Room\Guest;

use Module\Booking\Domain\Booking\Event\HotelBooking\GuestUnbinded;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\HotelBooking\BookingGuestRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Module\Booking\Domain\Guest\ValueObject\GuestId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class Unbind implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
        private readonly BookingGuestRepositoryInterface $bookingGuestRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(int $bookingId, int $roomBookingId, int $guestId): void
    {
        $booking = $this->bookingRepository->find(new BookingId($bookingId));
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }
        $roomId = new RoomBookingId($roomBookingId);
        $roomBooking = $this->roomBookingRepository->findOrFail($roomId);
        $newGuestId = new GuestId($guestId);
        $this->bookingGuestRepository->unbind($roomId, $newGuestId);
        $roomBooking->removeGuest($newGuestId);
        $this->roomBookingRepository->store($roomBooking);
        $this->eventDispatcher->dispatch(
            new GuestUnbinded(
                $booking->id(),
                $booking->orderId(),
                $roomId,
                $newGuestId,
            )
        );
    }
}
