<?php

namespace GTS\Integration\Traveline\Infrastructure\Facade;

use Custom\Framework\Contracts\Bus\CommandBusInterface;
use GTS\Integration\Traveline\Application\Command\UpdateQuotasAndPlans;
use GTS\Integration\Traveline\Application\Dto\HotelDto;
use GTS\Integration\Traveline\Application\Dto\RoomDto;
use GTS\Integration\Traveline\Domain\Adapter\HotelAdapterInterface;

class HotelFacade implements HotelFacadeInterface
{
    public function __construct(
        private CommandBusInterface   $commandBus,
        private HotelAdapterInterface $adapter
    ) {}

    public function getRoomsAndRatePlans(int $hotelId): HotelDto
    {
        $hotel = $this->adapter->getHotelById($hotelId);
        $rooms = $this->adapter->getRoomsAndRatePlans($hotelId);

        $roomsDto = RoomDto::collectionFromHotelRooms($rooms);
        return HotelDto::fromHotel($hotel, $roomsDto);
    }

    public function updateQuotasAndPlans()
    {
        $hotel = $this->commandBus->execute(new UpdateQuotasAndPlans());

        return $hotel; //TODO convert to DTO
    }
}
