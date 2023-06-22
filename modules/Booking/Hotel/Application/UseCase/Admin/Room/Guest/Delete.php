<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin\Room\Guest;

use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\Hotel\Infrastructure\Repository\BookingRepository;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Delete implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepository $repository,
        private readonly BookingUpdater $bookingUpdater,
    ) {}

    public function execute(int $bookingId, int $roomIndex, int $guestIndex): void
    {
        $booking = $this->repository->find($bookingId);
        $booking->deleteRoomBookingGuest($roomIndex, $guestIndex);
        $this->bookingUpdater->store($booking);
    }
}
