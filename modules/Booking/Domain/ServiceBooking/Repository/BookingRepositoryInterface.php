<?php

declare(strict_types=1);

namespace Module\Booking\Domain\ServiceBooking\Repository;

use Module\Booking\Domain\Order\ValueObject\OrderId;
use Module\Booking\Domain\ServiceBooking\ServiceBooking;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingPrice;
use Module\Booking\Domain\Shared\Repository\BookingRepositoryInterface as Base;
use Module\Booking\Domain\Shared\ValueObject\CancelConditions;
use Module\Booking\Domain\Shared\ValueObject\CreatorId;
use Module\Shared\Enum\ServiceTypeEnum;

interface BookingRepositoryInterface extends Base
{
    public function create(
        OrderId $orderId,
        CreatorId $creatorId,
        BookingPrice $price,
        CancelConditions $cancelConditions,
        ServiceTypeEnum $serviceType,
        ?string $note = null
    ): ServiceBooking;
}
