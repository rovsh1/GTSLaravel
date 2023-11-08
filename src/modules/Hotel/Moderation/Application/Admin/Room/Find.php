<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Admin\Room;

use Module\Hotel\Moderation\Application\Admin\Query\FindRoom;
use Module\Hotel\Moderation\Application\Admin\Response\RoomDto;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class Find implements UseCaseInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {}

    public function execute(int $id): ?RoomDto
    {
        return $this->queryBus->execute(new FindRoom($id));
    }
}
