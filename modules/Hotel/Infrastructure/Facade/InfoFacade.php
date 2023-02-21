<?php

namespace Module\Hotel\Infrastructure\Facade;

use Custom\Framework\Contracts\Bus\QueryBusInterface;
use Module\Hotel\Application\Dto\Info\HotelDto;
use Module\Hotel\Application\Dto\Info\RoomDto;
use Module\Hotel\Application\Query\GetHotelById;
use Module\Hotel\Application\Query\GetRoomsWithPriceRatesByHotelId;
use Module\Hotel\Domain\Entity\Hotel;
use Module\Hotel\Domain\Entity\Room;

class InfoFacade implements InfoFacadeInterface
{
    public function __construct(private QueryBusInterface $queryBus) {}

    public function findById(int $id): HotelDto
    {
        /** @var Hotel|null $hotel */
        $hotel = $this->queryBus->execute(new GetHotelById($id));

        return HotelDto::from($hotel);
    }

    public function getRoomsWithPriceRatesByHotelId(int $id): array
    {
        /** @var Room[] $rooms */
        $rooms = $this->queryBus->execute(new GetRoomsWithPriceRatesByHotelId($id));

        return RoomDto::collection($rooms)->all();
    }
}
