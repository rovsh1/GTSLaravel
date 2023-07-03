<?php

declare(strict_types=1);

namespace Module\Hotel\Application\UseCase;

use Module\Hotel\Application\Query\GetRoomMarkupSettings as Query;
use Module\Hotel\Application\Response\RoomMarkupSettingsDto;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetRoomMarkupSettings implements UseCaseInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function execute(int $hotelId, int $roomId): RoomMarkupSettingsDto
    {
        return $this->queryBus->execute(new Query($roomId));
    }
}
