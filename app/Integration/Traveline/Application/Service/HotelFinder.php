<?php

namespace GTS\Integration\Traveline\Application\Service;

use GTS\Integration\Traveline\Application\Dto\HotelDto;
use GTS\Integration\Traveline\Application\Dto\RoomDto;
use GTS\Integration\Traveline\Domain\Adapter\HotelAdapterInterface;
use GTS\Integration\Traveline\Domain\Exception\HotelNotConnectedException;
use GTS\Integration\Traveline\Domain\Repository\HotelRepositoryInterface;

class HotelFinder
{
    public function __construct(
        private HotelAdapterInterface    $adapter,
        private HotelRepositoryInterface $hotelRepository
    ) {}

    public function getHotelRoomsAndRatePlans(int $hotelId): HotelDto
    {
        $isHotelIntegrationEnabled = $this->hotelRepository->isHotelIntegrationEnabled($hotelId);
        if (!$isHotelIntegrationEnabled) {
            throw new HotelNotConnectedException();
        }

        $hotel = $this->adapter->getHotelById($hotelId);
        $rooms = $this->adapter->getRoomsAndRatePlans($hotelId);

        $roomsDto = RoomDto::collectionFromHotelRooms($rooms);
        return HotelDto::fromHotel($hotel, $roomsDto);
    }

}
