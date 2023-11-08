<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Admin\Price;

use Module\Hotel\Moderation\Application\Admin\Query\Price\Date\Get as Query;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetRoomPrices implements UseCaseInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {}

    public function execute(int $seasonId): array
    {
        return $this->queryBus->execute(new Query($seasonId));
    }
}
