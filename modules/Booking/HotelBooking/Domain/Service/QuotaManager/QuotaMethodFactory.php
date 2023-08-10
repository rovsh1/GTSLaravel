<?php

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager;

use Module\Booking\Common\Domain\Service\StatusRules\AdministratorRules;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;

class QuotaMethodFactory
{
    public function __construct(
        private readonly AdministratorRules $administratorRules
    ) {}

    public function build(QuotaProcessingMethodEnum $method): QuotaMethodInterface
    {
        return match ($method) {
            QuotaProcessingMethodEnum::QUOTE => new ByQuota($this->administratorRules),
            QuotaProcessingMethodEnum::REQUEST => new ByRequest(),
            QuotaProcessingMethodEnum::SITE => new BySite(),
        };
    }
}
