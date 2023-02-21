<?php

namespace Module\Hotel\Port\Controllers;

use Custom\Framework\Port\Request;
use Module\Hotel\Application\Dto\Info\HotelDto;
use Module\Hotel\Application\Dto\Info\RoomDto;
use Module\Hotel\Infrastructure\Facade\InfoFacadeInterface;

class InfoController
{
    public function __construct(
        private InfoFacadeInterface $infoFacade
    ) {}

    public function findById(Request $request): HotelDto
    {
        $request->validate([
            'id' => 'required|int',
        ]);
        return $this->infoFacade->findById($request->id);
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
        return $this->infoFacade->getRoomsWithPriceRatesByHotelId($request->id);
    }

}
