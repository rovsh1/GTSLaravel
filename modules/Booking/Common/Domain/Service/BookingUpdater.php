<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\Hotel\Infrastructure\Repository\BookingRepository as HotelBookingRepository;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;

class BookingUpdater
{
    public function __construct(
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    private function repository(BookingInterface $booking): BookingRepositoryInterface
    {
        return match ($booking->type()) {
            BookingTypeEnum::HOTEL => new HotelBookingRepository(),
            default => throw new \DomainException('Unknown booking type')
        };
    }

    public function store(BookingInterface $booking): bool
    {
        $success = $this->repository($booking)->store($booking);
        $this->eventDispatcher->dispatch(...$booking->pullEvents());

        return $success;
    }
}
