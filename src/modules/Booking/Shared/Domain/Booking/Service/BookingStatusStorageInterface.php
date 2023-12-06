<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Service;

use Sdk\Booking\Enum\StatusEnum;

interface BookingStatusStorageInterface
{
    public function getColor(StatusEnum $status): ?string;

    public function getName(StatusEnum $status): string;
}
