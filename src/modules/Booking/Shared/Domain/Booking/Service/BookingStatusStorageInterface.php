<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Service;

use Sdk\Shared\Enum\Booking\BookingStatusEnum;

interface BookingStatusStorageInterface
{
    public function getColor(BookingStatusEnum $status): ?string;

    public function getName(BookingStatusEnum $status): string;
}
