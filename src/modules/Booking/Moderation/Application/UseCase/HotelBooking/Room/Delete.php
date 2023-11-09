<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\HotelBooking\Room;

use Module\Booking\Shared\Domain\Booking\Event\HotelBooking\RoomDeleted;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Module\Shared\Contracts\Service\SafeExecutorInterface;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

final class Delete implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
        private readonly SafeExecutorInterface $executor,
    ) {
    }

    public function execute(int $bookingId, int $roomBookingId): void
    {
        $booking = $this->bookingRepository->findOrFail(new BookingId($bookingId));

        $this->executor->execute(function () use ($booking, $roomBookingId) {
            $roomBookingId = new RoomBookingId($roomBookingId);
            $this->roomBookingRepository->delete($roomBookingId);
            $this->eventDispatcher->dispatch(new RoomDeleted($booking, $roomBookingId));
        });
    }
}
