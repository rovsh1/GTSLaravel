<?php

declare(strict_types=1);

namespace Module\Hotel\Application\UseCase;

use Module\Hotel\Application\Query\Find as Query;
use Module\Hotel\Application\Response\HotelDto;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class FindHotelById implements UseCaseInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {}

    public function execute(int $id): ?HotelDto
    {
        return $this->queryBus->execute(new Query($id));
    }
}
