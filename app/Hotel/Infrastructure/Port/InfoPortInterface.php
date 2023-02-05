<?php

namespace GTS\Hotel\Infrastructure\Port;

use GTS\Hotel\Application\Dto\Info\HotelDto;
use GTS\Hotel\Application\Dto\Info\RoomDto;
use GTS\Shared\Application\Query\QueryBusInterface;

interface InfoPortInterface
{
    public function findById(int $id): HotelDto;

    /**
     * @param int $id
     * @return RoomDto[]
     */
    public function getRoomsWithPriceRatesByHotelId(int $id, QueryBusInterface $queryBus): array;
}
