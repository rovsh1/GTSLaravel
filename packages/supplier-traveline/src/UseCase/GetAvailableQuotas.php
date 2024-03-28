<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\UseCase;

use Carbon\CarbonPeriod;
use Module\Hotel\Quotation\Application\Dto\QuotaDto;
use Pkg\Supplier\Traveline\Repository\RoomQuotaRepository;

class GetAvailableQuotas
{
    public function __construct(
        private readonly RoomQuotaRepository $quotaRepository
    ) {}

    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return QuotaDto[]
     */
    public function execute(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->quotaRepository->getAvailable($hotelId, $period, $roomId);
    }
}
