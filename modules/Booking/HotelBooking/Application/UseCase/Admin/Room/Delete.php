<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin\Room;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\HotelBooking\Domain\Service\RoomUpdater\RoomUpdater;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Delete implements UseCaseInterface
{
    public function __construct(
        private readonly RoomUpdater $roomUpdater,
    ) {}

    public function execute(int $bookingId, int $roomBookingId): void
    {
        $this->roomUpdater->delete(
            new BookingId($bookingId),
            new RoomBookingId($roomBookingId)
        );
    }
}
