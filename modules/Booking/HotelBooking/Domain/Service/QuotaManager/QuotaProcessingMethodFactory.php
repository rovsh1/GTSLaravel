<?php

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager;

use Module\Booking\Common\Domain\Service\StatusRules\AdministratorRules;
use Module\Booking\HotelBooking\Domain\Adapter\HotelQuotaAdapterInterface;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\ProcessingMethod\Quota;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\ProcessingMethod\Request;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\ProcessingMethod\Site;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;

class QuotaProcessingMethodFactory
{
    public function __construct(
        private readonly AdministratorRules $administratorRules,
        private readonly HotelQuotaAdapterInterface $hotelQuotaAdapter,
    ) {}

    public function build(QuotaProcessingMethodEnum $method): QuotaProcessingMethodInterface
    {
        return match ($method) {
            QuotaProcessingMethodEnum::QUOTA => new Quota($this->administratorRules, $this->hotelQuotaAdapter),
            QuotaProcessingMethodEnum::REQUEST => new Request(),
            QuotaProcessingMethodEnum::SITE => new Site(),
        };
    }
}
