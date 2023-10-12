<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\HotelBooking\UseCase\Room;

use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Domain\Booking\Service\HotelBooking\RoomUpdater\RoomUpdater;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\HotelBooking\RoomBookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class Delete implements UseCaseInterface
{
    public function __construct(
        private readonly RoomUpdater $roomUpdater,
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly HotelBookingRepositoryInterface $detailsRepository,
    ) {
    }

    public function execute(int $bookingId, int $roomBookingId): void
    {
        $booking = $this->bookingRepository->find(new BookingId($bookingId));
        if ($booking === null) {
            throw new EntityNotFoundException('Booking not found');
        }
        $details = $this->detailsRepository->find($booking->id());
        $this->roomUpdater->delete(
            $booking,
            $details,
            new RoomBookingId($roomBookingId)
        );
    }
}
