<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\UseCase\QuotaAvailability;

use Carbon\CarbonPeriod;
use Pkg\Supplier\Traveline\Dto\QuotaDto;
use Pkg\Supplier\Traveline\Repository\RoomQuotaRepository;

class GetSold
{
    public function __construct(
        private readonly RoomQuotaRepository $quotaRepository
    ) {}

    /**
     * @param CarbonPeriod $period
     * @param array $cityIds
     * @param array $hotelIds
     * @param array $roomIds
     * @param array $roomTypeIds
     * @return QuotaDto
     */
    public function execute(
        CarbonPeriod $period,
        array $cityIds = [],
        array $hotelIds = [],
        array $roomIds = [],
        array $roomTypeIds = []
    ): array {
        return $this->quotaRepository->getQuotasSoldAvailability($period, $cityIds, $hotelIds, $roomIds, $roomTypeIds);
    }
}
