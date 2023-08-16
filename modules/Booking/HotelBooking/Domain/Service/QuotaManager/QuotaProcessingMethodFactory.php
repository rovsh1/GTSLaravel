<?php

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager;

use Module\Booking\Common\Domain\Service\StatusRules\AdministratorRules;
use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\ProcessingMethod\Quota;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\ProcessingMethod\Quota\QuotaReservationManager;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\ProcessingMethod\Request;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\ProcessingMethod\Site;
use Module\Shared\Enum\Booking\QuotaProcessingMethodEnum;

class QuotaProcessingMethodFactory
{
    public function __construct(
        private readonly AdministratorRules $administratorRules,
        private readonly QuotaReservationManager $quotaReservationManager,
        private readonly BookingRepositoryInterface $bookingRepository
    ) {}

    public function build(QuotaProcessingMethodEnum $method): QuotaProcessingMethodInterface
    {
        return match ($method) {
            QuotaProcessingMethodEnum::QUOTA => new Quota($this->administratorRules, $this->quotaReservationManager, $this->bookingRepository),
            QuotaProcessingMethodEnum::REQUEST => new Request(),
            QuotaProcessingMethodEnum::SITE => new Site(),
        };
    }
}
