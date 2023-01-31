<?php

namespace GTS\Hotel\Infrastructure\Facade\Info;

use GTS\Hotel\Application\Dto\Info\HotelDto;
use GTS\Hotel\Application\Dto\Info\RoomDto;
use GTS\Hotel\Application\Query\GetHotelById;
use GTS\Hotel\Application\Query\GetRoomsByHotelId;
use GTS\Hotel\Domain\Entity\Hotel;
use GTS\Hotel\Domain\Entity\Room;
use GTS\Shared\Application\Query\QueryBusInterface;

class Facade implements FacadeInterface
{
    public function __construct(private QueryBusInterface $queryBus) {}

    public function findById(int $id): HotelDto
    {
        /** @var Hotel|null $hotel */
        $hotel = $this->queryBus->execute(new GetHotelById($id));

        return HotelDto::from($hotel);
    }

    public function getRoomsByHotelId(int $id): array
    {
        /** @var Room[] $rooms */
        $rooms = $this->queryBus->execute(new GetRoomsByHotelId($id));

        return RoomDto::collection($rooms)->all();
    }
}
