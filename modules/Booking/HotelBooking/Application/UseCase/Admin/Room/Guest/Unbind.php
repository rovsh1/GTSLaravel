<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin\Room\Guest;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Common\Domain\ValueObject\OrderId;
use Module\Booking\HotelBooking\Domain\Event\GuestUnbinded;
use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Repository\BookingGuestRepositoryInterface;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingId;
use Module\Booking\Order\Domain\ValueObject\GuestId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class Unbind implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly BookingGuestRepositoryInterface $bookingGuestRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(int $bookingId, int $roomBookingId, int $guestId): void
    {
        $booking = $this->bookingRepository->find($bookingId);
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }
        $roomId = new RoomBookingId($roomBookingId);
        $newGuestId = new GuestId($guestId);
        $this->bookingGuestRepository->unbind($roomId, $newGuestId);
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
