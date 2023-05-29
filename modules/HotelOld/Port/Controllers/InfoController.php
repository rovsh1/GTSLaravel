<?php

namespace Module\HotelOld\Port\Controllers;

use Module\HotelOld\Application\Dto\Info\HotelDto;
use Module\HotelOld\Application\Dto\Info\RoomDto;
use Module\HotelOld\Application\Query\GetHotelById;
use Module\HotelOld\Application\Query\GetRoomsWithPriceRatesByHotelId;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\PortGateway\Request;

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
     * @param \Sdk\Module\PortGateway\Request $request
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
