<?php

namespace Module\Booking\Domain\HotelBooking\Service\QuotaManager;

use Module\Booking\Domain\HotelBooking\Service\QuotaManager\ProcessingMethod\Quota;
use Module\Booking\Domain\HotelBooking\Service\QuotaManager\ProcessingMethod\Quota\QuotaReservationManager;
use Module\Booking\Domain\HotelBooking\Service\QuotaManager\ProcessingMethod\Request;
use Module\Booking\Domain\HotelBooking\Service\QuotaManager\ProcessingMethod\Site;
use Module\Booking\Domain\Shared\Service\StatusRules\AdministratorRules;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;

class QuotaProcessingMethodFactory
{
    public function __construct(
        private readonly AdministratorRules $administratorRules,
        private readonly QuotaReservationManager $quotaReservationManager,
    ) {}

    public function build(QuotaProcessingMethodEnum $method): QuotaProcessingMethodInterface
    {
        return match ($method) {
            QuotaProcessingMethodEnum::QUOTA => new Quota($this->administratorRules, $this->quotaReservationManager),
            QuotaProcessingMethodEnum::REQUEST => new Request(),
            QuotaProcessingMethodEnum::SITE => new Site(),
        };
    }
}
