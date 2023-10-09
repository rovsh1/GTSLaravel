<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Shared\Service;

use Module\Booking\Deprecated\AirportBooking\AirportBooking;
use Module\Booking\Deprecated\HotelBooking\HotelBooking;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Infrastructure\HotelBooking\Repository\BookingRepository as HotelBookingRepository;
use Module\Shared\Enum\ServiceTypeEnum;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\ModuleInterface;

class BookingUpdater
{
    public function __construct(
        private readonly DomainEventDispatcherInterface $eventDispatcher,
        private readonly ModuleInterface $module
    ) {}

    public function store(BookingInterface $booking): bool
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
    public function storeIfHasEvents(BookingInterface $booking): bool
    {
        $events = $booking->pullEvents();
        if (count($events) === 0) {
            return true;
        }
        $success = $this->repository($booking)->store($booking);
        $this->eventDispatcher->dispatch(...$events);

        return $success;
    }

    private function repository(BookingInterface $booking): HotelBookingRepository|BookingRepositoryInterface
    {
        return match ($booking->serviceType()) {
            ServiceTypeEnum::HOTEL_BOOKING => $this->module->get(HotelBookingRepository::class),
            ServiceTypeEnum::CIP_IN_AIRPORT,
            ServiceTypeEnum::CAR_RENT,
            ServiceTypeEnum::TRANSFER_TO_RAILWAY,
            ServiceTypeEnum::TRANSFER_FROM_RAILWAY,
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT,
            ServiceTypeEnum::TRANSFER_TO_AIRPORT => $this->module->get(BookingRepositoryInterface::class),
            default => throw new \DomainException('Unknown booking type')
        };
    }
}
