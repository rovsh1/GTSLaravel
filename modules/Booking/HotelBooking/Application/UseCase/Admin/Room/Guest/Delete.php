<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin\Room\Guest;

use Module\Booking\HotelBooking\Domain\Repository\RoomBookingRepositoryInterface;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Delete implements UseCaseInterface
{
    public function __construct(
        private readonly RoomBookingRepositoryInterface $repository,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(int $bookingId, int $roomBookingId, int $guestIndex): void
    {
        $roomBooking = $this->repository->find($roomBookingId);
        $roomBooking->deleteGuest($guestIndex);
        $this->repository->store($roomBooking);
        $this->eventDispatcher->dispatch(...$roomBooking->pullEvents());
    }
}
