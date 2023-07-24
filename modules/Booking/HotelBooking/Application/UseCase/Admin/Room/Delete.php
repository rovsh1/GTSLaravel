<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin\Room;

use Module\Booking\HotelBooking\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\HotelBooking\Infrastructure\Repository\BookingRepository;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Delete implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepository $repository,
        private readonly RoomBookingRepositoryInterface $roomBookingRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(int $bookingId, int $roomBookingId): void
    {
        $booking = $this->repository->find($bookingId);
        $booking->deleteRoomBooking($roomBookingId);
        $this->roomBookingRepository->delete($roomBookingId);
        $this->eventDispatcher->dispatch(...$booking->pullEvents());
    }
}
