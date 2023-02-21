<?php

namespace Module\Hotel\Infrastructure\Facade;

use Module\Hotel\Application\Dto\Info\HotelDto;
use Module\Hotel\Application\Dto\Info\RoomDto;

interface InfoFacadeInterface
{
    public function findById(int $id): HotelDto;

    /**
     * @param int $id
     * @return RoomDto[]
     */
    public function getRoomsWithPriceRatesByHotelId(int $id): array;
}
