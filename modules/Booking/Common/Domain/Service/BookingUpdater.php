<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service;

use Module\Booking\Airport\Domain\Entity\Booking as AirportBooking;
use Module\Booking\Airport\Infrastructure\Repository\BookingRepository as AirportBookingRepository;
use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\HotelBooking\Domain\Entity\Booking as HotelBooking;
use Module\Booking\HotelBooking\Infrastructure\Repository\BookingRepository as HotelBookingRepository;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\ModuleInterface;

class BookingUpdater
{
    public function __construct(
        private readonly DomainEventDispatcherInterface $eventDispatcher,
        private readonly ModuleInterface $module
    ) {}

    public function store(HotelBooking|AirportBooking $booking): bool
    {
        $success = $this->repository($booking)->store($booking);
        $this->eventDispatcher->dispatch(...$booking->pullEvents());

        return $success;
    }

    /**
     * @param HotelBooking|AirportBooking $booking
     * @return bool
     * @todo подумать как точно провернить, что были изменения в броне
     */
    public function storeIfHasEvents(HotelBooking|AirportBooking $booking): bool
    {
        $events = $booking->pullEvents();
        if (count($events) === 0) {
            return true;
        }
        $success = $this->repository($booking)->store($booking);
        $this->eventDispatcher->dispatch(...$events);

        return $success;
    }

    private function repository(BookingInterface $booking): HotelBookingRepository|AirportBookingRepository
    {
        return match ($booking->type()) {
            BookingTypeEnum::HOTEL => $this->module->get(HotelBookingRepository::class),
            BookingTypeEnum::AIRPORT => $this->module->get(AirportBookingRepository::class),
            default => throw new \DomainException('Unknown booking type')
        };
    }
}
