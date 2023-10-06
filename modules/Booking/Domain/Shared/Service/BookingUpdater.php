<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Service;

use Module\Booking\Domain\AirportBooking\AirportBooking;
use Module\Booking\Domain\HotelBooking\HotelBooking;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\ValueObject\BookingTypeEnum;
use Module\Booking\Domain\TransferBooking\TransferBooking;
use Module\Booking\Infrastructure\AirportBooking\Repository\BookingRepository as AirportBookingRepository;
use Module\Booking\Infrastructure\HotelBooking\Repository\BookingRepository as HotelBookingRepository;
use Module\Booking\Infrastructure\TransferBooking\Repository\BookingRepository as TransferBookingRepository;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\ModuleInterface;

class BookingUpdater
{
    public function __construct(
        private readonly DomainEventDispatcherInterface $eventDispatcher,
        private readonly ModuleInterface $module
    ) {}

    public function store(HotelBooking|AirportBooking|TransferBooking $booking): bool
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
    public function storeIfHasEvents(HotelBooking|AirportBooking|TransferBooking $booking): bool
    {
        $events = $booking->pullEvents();
        if (count($events) === 0) {
            return true;
        }
        $success = $this->repository($booking)->store($booking);
        $this->eventDispatcher->dispatch(...$events);

        return $success;
    }

    private function repository(BookingInterface $booking): HotelBookingRepository|AirportBookingRepository|TransferBookingRepository
    {
        return match ($booking->type()) {
            BookingTypeEnum::HOTEL => $this->module->get(HotelBookingRepository::class),
            BookingTypeEnum::AIRPORT => $this->module->get(AirportBookingRepository::class),
            BookingTypeEnum::TRANSFER => $this->module->get(TransferBookingRepository::class),
            default => throw new \DomainException('Unknown booking type')
        };
    }
}
