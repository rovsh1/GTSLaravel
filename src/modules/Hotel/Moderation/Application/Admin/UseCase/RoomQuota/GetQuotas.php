<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Admin\UseCase\RoomQuota;

use Carbon\CarbonPeriod;
use Module\Hotel\Moderation\Application\Admin\Query\Quota\Get;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetQuotas implements UseCaseInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {
    }

    public function execute(int $hotelId, CarbonPeriod $period, ?int $roomId): array
    {
        return $this->queryBus->execute(
            new Get(
                $hotelId,
                $period,
                $roomId
            )
        );
    }
}