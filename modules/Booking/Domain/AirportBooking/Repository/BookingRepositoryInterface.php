<?php

namespace Module\Booking\Domain\AirportBooking\Repository;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Domain\AirportBooking\AirportBooking;
use Module\Booking\Domain\AirportBooking\ValueObject\Details\AdditionalInfo;
use Module\Booking\Domain\Shared\Entity\BookingInterface;
use Module\Booking\Domain\Shared\Repository\BookingRepositoryInterface as Base;
use Module\Booking\Domain\Shared\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\ValueObject\CancelConditions;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Booking\Domain\Shared\ValueObject\OrderId;

interface BookingRepositoryInterface extends Base
{
    public function find(int $id): ?AirportBooking;

    public function findOrFail(BookingId $id): AirportBooking;

    public function get(): Collection;

    public function create(
        OrderId $orderId,
        CreatorId $creatorId,
        int $serviceId,
        int $airportId,
        CarbonInterface $date,
        BookingPrice $price,
        AdditionalInfo $additionalInfo,
        CancelConditions $cancelConditions,
        ?string $note = null
    ): AirportBooking;

    public function store(AirportBooking $booking): bool;

    public function delete(BookingInterface|AirportBooking $booking): void;
}
