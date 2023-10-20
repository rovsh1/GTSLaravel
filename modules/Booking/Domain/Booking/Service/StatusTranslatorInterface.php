<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Service;

use Module\Booking\Domain\Shared\ValueObject\BookingStatusEnum;

interface StatusTranslatorInterface
{
    public function getName(BookingStatusEnum $status): string;
}
