<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Admin\UseCase\Price;

use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetSeasonsPrices implements UseCaseInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {
    }

    public function execute(int $hotelId): array
    {
        return $this->queryBus->execute(
            new \Module\Hotel\Moderation\Application\Admin\Query\Price\Season\Get($hotelId),
        );
    }
}
