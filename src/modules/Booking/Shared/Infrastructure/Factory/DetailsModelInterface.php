<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Factory;

use Module\Shared\Enum\ServiceTypeEnum;

interface DetailsModelInterface
{
    public function bookingId(): int;

    public function serviceType(): ServiceTypeEnum;
}
