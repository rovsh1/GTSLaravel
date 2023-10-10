<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\ServiceBooking\Models\Details;

use Module\Shared\Enum\ServiceTypeEnum;

interface DetailsModelInterface
{
    public function bookingId(): int;

    public function serviceType(): ServiceTypeEnum;
}
