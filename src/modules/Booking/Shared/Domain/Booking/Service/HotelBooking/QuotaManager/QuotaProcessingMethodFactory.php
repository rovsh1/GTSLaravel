<?php

namespace Module\Booking\Shared\Domain\Booking\Service\HotelBooking\QuotaManager;

use Module\Booking\Moderation\Domain\Booking\Service\StatusRules\AdministratorRules;
use Module\Booking\Shared\Domain\Booking\Service\HotelBooking\QuotaManager\ProcessingMethod\Quota;
use Module\Booking\Shared\Domain\Booking\Service\HotelBooking\QuotaManager\ProcessingMethod\Quota\QuotaReservationManager;
use Module\Booking\Shared\Domain\Booking\Service\HotelBooking\QuotaManager\ProcessingMethod\Request;
use Module\Booking\Shared\Domain\Booking\Service\HotelBooking\QuotaManager\ProcessingMethod\Site;
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
