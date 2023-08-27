<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin\Room\Tourist;

use Module\Booking\HotelBooking\Domain\Event\GuestDeleted;
use Module\Booking\HotelBooking\Domain\Repository\BookingTouristRepositoryInterface;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingId;
use Module\Booking\Order\Domain\ValueObject\TouristId;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Unbind implements UseCaseInterface
{
    public function __construct(
        private readonly BookingTouristRepositoryInterface $bookingTouristRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function execute(int $bookingId, int $roomBookingId, int $touristId): void
    {
        $this->bookingTouristRepository->unbind(
            new RoomBookingId($roomBookingId),
            new TouristId($touristId)
        );
        $this->eventDispatcher->dispatch(
            new GuestDeleted()
        );
    }
}
