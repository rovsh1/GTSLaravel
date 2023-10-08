<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\UseCase\Room;

use Module\Booking\Domain\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\HotelBooking\Service\RoomUpdater\RoomUpdater;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\RoomBooking\RoomBookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class Delete implements UseCaseInterface
{
    public function __construct(
        private readonly RoomUpdater $roomUpdater,
        private readonly BookingRepositoryInterface $bookingRepository,
    ) {}

    public function execute(int $bookingId, int $roomBookingId): void
    {
        $booking = $this->bookingRepository->find($bookingId);
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }
        $this->roomUpdater->delete(
            $booking,
            new RoomBookingId($roomBookingId)
        );
    }
}
