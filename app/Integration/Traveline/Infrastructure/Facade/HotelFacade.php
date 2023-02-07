<?php

namespace GTS\Integration\Traveline\Infrastructure\Facade;

use GTS\Integration\Traveline\Application\Command\UpdateQuotasAndPlans;
use GTS\Integration\Traveline\Application\Dto\HotelDto;
use GTS\Integration\Traveline\Application\Dto\RoomDto;
use GTS\Integration\Traveline\Domain\Adapter\HotelAdapterInterface;
use GTS\Shared\Application\Command\CommandBusInterface;

class HotelFacade implements HotelFacadeInterface
{
    public function __construct(
        private CommandBusInterface   $commandBus,
        private HotelAdapterInterface $adapter
    ) {}

    public function getRoomsAndRatePlans(int $hotelId)
    {
        $hotel = $this->adapter->getHotelById($hotelId);
        $rooms = $this->adapter->getRoomsAndRatePlans($hotelId);

        $roomsDto = RoomDto::collection($rooms);
        $hotelDto = HotelDto::from($hotel)->additional([
            'roomsAndRatePlans' => $roomsDto
        ]);
        return $hotelDto;
    }

    public function updateQuotasAndPlans()
    {
        $hotel = $this->commandBus->execute(new UpdateQuotasAndPlans());

        return $hotel; //TODO convert to DTO
    }
}
