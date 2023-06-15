<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service;

use Module\Booking\Airport\Domain\Entity\Booking as AirportBooking;
use Module\Booking\Airport\Infrastructure\Repository\BookingRepository as AirportBookingRepository;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Hotel\Domain\Entity\Booking as HotelBooking;
use Module\Booking\Hotel\Infrastructure\Repository\BookingRepository as HotelBookingRepository;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;

class BookingUpdater
{
    public function __construct(
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    public function store(HotelBooking|AirportBooking $booking): bool
    {
        $success = $this->repository($booking)->store($booking);
        $this->eventDispatcher->dispatch(...$booking->pullEvents());

        return $success;
    }

    private function repository(BookingInterface $booking): HotelBookingRepository|AirportBookingRepository
    {
        return match ($booking->type()) {
            BookingTypeEnum::HOTEL => new HotelBookingRepository(),
            BookingTypeEnum::AIRPORT => new AirportBookingRepository(),
            default => throw new \DomainException('Unknown booking type')
        };
    }
}
