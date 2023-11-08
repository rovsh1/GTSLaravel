<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Admin\Quota;

use Carbon\CarbonPeriod;
use Module\Hotel\Moderation\Application\Admin\Response\QuotaDto;
use Module\Hotel\Moderation\Domain\Hotel\Repository\RoomQuotaRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetAvailable implements UseCaseInterface
{
    public function __construct(
        private readonly RoomQuotaRepositoryInterface $repository
    ) {}

    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return array<int, QuotaDto>
     */
    public function execute(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        $quotas = $this->repository->getAvailable($hotelId, $period, $roomId);

        return QuotaDto::collection($quotas)->all();
    }
}
