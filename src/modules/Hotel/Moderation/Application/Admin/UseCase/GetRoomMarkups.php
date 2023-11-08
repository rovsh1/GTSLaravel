<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Admin\UseCase;

use Module\Hotel\Moderation\Application\Admin\Query\GetRoomMarkups as Query;
use Module\Hotel\Moderation\Application\Admin\Response\RoomMarkupsDto;
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
