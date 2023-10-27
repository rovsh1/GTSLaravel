<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Repository\Details;

use DateTimeInterface;
use Module\Booking\Domain\Booking\Entity\DayCarTrip;
use Module\Booking\Domain\Booking\Entity\TransferToAirport;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\CarBidCollection;
use Module\Booking\Domain\Booking\ValueObject\ServiceInfo;

interface DayCarTripRepositoryInterface
{
    public function find(BookingId $bookingId): ?DayCarTrip;

    public function create(
        BookingId $bookingId,
        ServiceInfo $serviceInfo,
        int $cityId,
        CarBidCollection $carBids,
        ?string $destinationsDescription,
        ?DateTimeInterface $departureDate,
    ): DayCarTrip;

    public function store(DayCarTrip $details): bool;
}
