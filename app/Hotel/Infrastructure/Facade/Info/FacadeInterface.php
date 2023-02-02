<?php

namespace GTS\Hotel\Infrastructure\Facade\Info;

use GTS\Hotel\Application\Dto\Info\HotelDto;
use GTS\Hotel\Application\Dto\Info\RoomDto;

interface FacadeInterface
{
    public function findById(int $id): HotelDto;

    /**
     * @param int $id
     * @return RoomDto[]
     */
    public function getRoomsWithPriceRateByHotelId(int $id): array;
}
