<?php

declare(strict_types=1);

namespace Module\Hotel\Quotation\Application\UseCase;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Application\Service\QuotaFetcher;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailableCount implements UseCaseInterface
{
    public function __construct(
        private readonly QuotaFetcher $quotaFetcher,
    ) {}

    public function execute(int $hotelId, int $roomId, CarbonPeriod $period): int
    {
        return $this->quotaFetcher->getAvailableCount($hotelId, $roomId, $period);
    }
}
