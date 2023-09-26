<?php

declare(strict_types=1);

namespace Module\Hotel\Application\UseCase\Room;

use Module\Hotel\Application\Query\FindRoom;
use Module\Hotel\Application\Response\RoomDto;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Find implements UseCaseInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {}

    public function execute(int $id): RoomDto
    {
        return $this->queryBus->execute(new FindRoom($id));
    }
}
