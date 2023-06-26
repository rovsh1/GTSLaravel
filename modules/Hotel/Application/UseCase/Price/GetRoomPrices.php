<?php

declare(strict_types=1);

namespace Module\Hotel\Application\UseCase\Price;

use Module\Hotel\Application\Dto\HotelDto;
use Module\Hotel\Application\Query\Price\Date\Get as Query;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetRoomPrices implements UseCaseInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {}

    public function execute(int $seasonId): HotelDto
    {
        return $this->queryBus->execute(new Query($seasonId));
    }
}
