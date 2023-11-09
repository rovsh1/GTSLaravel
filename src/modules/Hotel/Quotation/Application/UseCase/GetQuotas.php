<?php

declare(strict_types=1);

namespace Module\Hotel\Quotation\Application\UseCase;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Application\Dto\QuotaDto;
use Module\Hotel\Quotation\Application\Service\QuotaFetcher;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetQuotas implements UseCaseInterface
{
    public function __construct(
        private readonly QuotaFetcher $quotaFetcher
    ) {
    }

    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return QuotaDto[]
     */
    public function execute(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->quotaFetcher->getAll($hotelId, $period, $roomId);
    }
}
