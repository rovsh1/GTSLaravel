<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Repository;

use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Domain\Order\ValueObject\OrderId;
use Module\Booking\Domain\Shared\ValueObject\CancelConditions;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Shared\Enum\ServiceTypeEnum;

interface BookingRepositoryInterface
{
    public function create(
        OrderId $orderId,
        CreatorId $creatorId,
        BookingPrices $prices,
        CancelConditions $cancelConditions,
        ServiceTypeEnum $serviceType,
        ?string $note = null
    ): Booking;

    public function find(BookingId $id): ?Booking;

    public function findOrFail(BookingId $id): Booking;
}
