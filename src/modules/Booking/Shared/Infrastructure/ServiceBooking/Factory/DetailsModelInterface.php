<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\ServiceBooking\Factory;

use Module\Shared\Enum\ServiceTypeEnum;

interface DetailsModelInterface
{
    public function bookingId(): int;

    public function serviceType(): ServiceTypeEnum;
}
