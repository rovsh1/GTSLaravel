<?php

declare(strict_types=1);

namespace Module\Catalog\Application\Admin\UseCase;

use Module\Catalog\Application\Admin\Query\GetRoomMarkups as Query;
use Module\Catalog\Application\Admin\Response\RoomMarkupsDto;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetRoomMarkups implements UseCaseInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function execute(int $roomId): ?RoomMarkupsDto
    {
        return $this->queryBus->execute(new Query($roomId));
    }
}
