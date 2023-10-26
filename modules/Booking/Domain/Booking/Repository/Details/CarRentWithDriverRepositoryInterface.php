<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Repository\Details;

use DateTimeInterface;
use Module\Booking\Domain\Booking\Entity\CarRentWithDriver;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\BookingPeriod;
use Module\Booking\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Domain\Booking\ValueObject\ServiceInfo;

interface CarRentWithDriverRepositoryInterface
{
    public function find(BookingId $bookingId): ?CarRentWithDriver;

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $cityId,
        ?bool $hoursLimit,
        CarBidCollection $carBids,
        ?BookingPeriod $bookingPeriod,
    ): CarRentWithDriver;

    public function store(CarRentWithDriver $details): bool;
}
