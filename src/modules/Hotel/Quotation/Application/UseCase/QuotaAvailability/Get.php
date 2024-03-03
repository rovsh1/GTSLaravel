<?php

declare(strict_types=1);

namespace Module\Hotel\Quotation\Application\UseCase\QuotaAvailability;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Application\Dto\QuotaDto;
use Module\Hotel\Quotation\Application\Service\QuotaAvailabilityFetcher;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Get implements UseCaseInterface
{
    public function __construct(
        private readonly QuotaAvailabilityFetcher $quotaFetcher
    ) {}

    /**
     * @param CarbonPeriod $period
     * @param array $cityIds
     * @param array $hotelIds
     * @param array $roomIds
     * @return QuotaDto[]
     */
    public function execute(CarbonPeriod $period, array $cityIds = [], array $hotelIds = [], array $roomIds = []): array
    {
        return $this->quotaFetcher->getQuotasAvailability($period, $cityIds, $hotelIds, $roomIds);
    }
}
