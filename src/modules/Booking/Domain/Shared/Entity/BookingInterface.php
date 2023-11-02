<?php

namespace Module\Booking\Domain\Shared\Entity;

use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\Context;
use Module\Booking\Domain\Order\ValueObject\OrderId;
use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Enum\ServiceTypeEnum;

interface BookingInterface extends EntityInterface
{
    public function id(): BookingId;

    public function orderId(): OrderId;

    public function status(): BookingStatusEnum;

    public function context(): Context;

    public function serviceType(): ServiceTypeEnum;
}
