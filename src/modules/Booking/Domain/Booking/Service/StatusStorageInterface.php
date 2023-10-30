<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Service;

use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;

interface StatusStorageInterface
{
    public function getColor(BookingStatusEnum $status): ?string;

    public function getName(BookingStatusEnum $status): string;
}
