<?php

namespace GTS\Hotel\UI\Port\Controllers;

use Custom\Framework\Port\Request;
use GTS\Hotel\Application\Dto\Info\HotelDto;
use GTS\Hotel\Application\Dto\Info\RoomDto;
use GTS\Hotel\Infrastructure\Facade\InfoFacadeInterface;

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
