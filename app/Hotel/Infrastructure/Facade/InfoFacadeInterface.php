<?php

namespace GTS\Hotel\Infrastructure\Facade;

use GTS\Hotel\Application\Dto\Info\HotelDto;
use GTS\Hotel\Application\Dto\Info\RoomDto;

interface InfoFacadeInterface
{
    public function findById(int $id): HotelDto;

    /**
     * @param int $id
     * @return RoomDto[]
     */
    public function getRoomsWithPriceRatesByHotelId(int $id): array;
}
