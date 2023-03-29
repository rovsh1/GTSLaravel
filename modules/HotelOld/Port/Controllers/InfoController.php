<?php

namespace Module\HotelOld\Port\Controllers;

use Custom\Framework\Contracts\Bus\QueryBusInterface;
use Custom\Framework\Port\Request;
use Module\HotelOld\Application\Dto\Info\HotelDto;
use Module\HotelOld\Application\Dto\Info\RoomDto;
use Module\HotelOld\Application\Query\GetHotelById;
use Module\HotelOld\Application\Query\GetRoomsWithPriceRatesByHotelId;

class InfoController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {}

    public function findById(Request $request): HotelDto
    {
        $request->validate([
            'id' => 'required|int',
        ]);
        return $this->queryBus->execute(new GetHotelById($request->id));
    }

    /**
     * @param Request $request
     * @return RoomDto[]
     */
    public function getRoomsWithPriceRatesByHotelId(Request $request): array
    {
        $request->validate([
            'id' => 'required|int',
        ]);
        return $this->queryBus->execute(new GetRoomsWithPriceRatesByHotelId($request->id));
    }

}
