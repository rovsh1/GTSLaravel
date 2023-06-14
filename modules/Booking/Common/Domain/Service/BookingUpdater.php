<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Service;

use Module\Booking\Common\Domain\Entity\BookingInterface;
use Module\Booking\Hotel\Infrastructure\Repository\BookingRepository;

class BookingUpdater
{
    public function __construct(
        private readonly BookingRepository $hotelBookingRepository
    ) {}

    public function store(BookingInterface $booking): bool {

    }
}
