<?php

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager;

use Module\Booking\HotelBooking\Domain\ValueObject\QuotaProcessingMethodEnum;

class QuotaMethodFactory
{
    public function build(QuotaProcessingMethodEnum $method): QuotaMethodInterface
    {
        return match ($method) {
            QuotaProcessingMethodEnum::QUOTE => new ByQuota(),
            QuotaProcessingMethodEnum::REQUEST => new ByRequest(),
        };
    }
}
