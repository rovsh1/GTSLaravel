<?php

declare(strict_types=1);

namespace Module\Hotel\Application\UseCase;

use Module\Hotel\Application\Dto\HotelDto;
use Module\Hotel\Application\Query\GetHotelById as Query;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetHotelById implements UseCaseInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {}

    public function execute(int $id): HotelDto
    {
        return $this->queryBus->execute(new Query($id));
    }
}
