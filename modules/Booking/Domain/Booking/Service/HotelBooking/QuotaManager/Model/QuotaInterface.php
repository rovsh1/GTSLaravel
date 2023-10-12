<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Service\HotelBooking\QuotaManager\Model;

use Carbon\CarbonInterface;

interface QuotaInterface
{
    public function roomId(): int;

    public function date(): CarbonInterface;
}
