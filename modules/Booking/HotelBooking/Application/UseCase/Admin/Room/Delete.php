<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin\Room;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\HotelBooking\Domain\Service\RoomBookingService;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Delete implements UseCaseInterface
{
    public function __construct(
        private readonly RoomBookingService $roomBookingService
    ) {}

    public function execute(int $bookingId, int $roomBookingId): void
    {
        $this->roomBookingService->deleteRoomBooking(
            new BookingId($bookingId),
            new RoomBookingId($roomBookingId)
        );
    }
}
