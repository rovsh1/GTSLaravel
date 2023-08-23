<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager\Model;

use Carbon\CarbonInterface;

interface QuotaInterface
{
    public function roomId(): int;

    public function date(): CarbonInterface;
}