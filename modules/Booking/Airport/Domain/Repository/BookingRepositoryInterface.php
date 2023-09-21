<?php

namespace Module\Booking\Airport\Domain\Repository;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Airport\Domain\Entity\Booking;
use Module\Booking\Airport\Domain\ValueObject\Details\AdditionalInfo;
use Module\Booking\Common\Domain\Repository\BookingRepositoryInterface as Base;
use Module\Booking\Common\Domain\ValueObject\BookingPrice;
use Module\Booking\Common\Domain\ValueObject\CancelConditions;
use Module\Booking\Common\Domain\ValueObject\CreatorId;
use Module\Booking\Common\Domain\ValueObject\OrderId;

interface BookingRepositoryInterface extends Base
{
    public function find(int $id): ?Booking;

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
    ): Booking;

    public function store(Booking $booking): bool;
}
