<?php

namespace GTS\Services\Integration\Traveline\Infrastructure\Facade;

use GTS\Services\Integration\Traveline\Application\Command\UpdateQuotasAndPlans;
use GTS\Services\Integration\Traveline\Application\Dto\HotelDto;
use GTS\Services\Integration\Traveline\Application\Dto\RoomDto;
use GTS\Services\Integration\Traveline\Domain\Adapter\HotelAdapterInterface;
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
        $hotelDto = HotelDto::from($hotel)->additional($roomsDto);
        return $hotelDto;
    }

    public function updateQuotasAndPlans()
    {
        $hotel = $this->commandBus->execute(new UpdateQuotasAndPlans());

        return $hotel; //TODO convert to DTO
    }
}
