<?php

namespace Module\Integration\Traveline\Application\Service;

use Module\Integration\Traveline\Application\Dto\HotelDto;
use Module\Integration\Traveline\Application\Dto\RoomDto;
use Module\Integration\Traveline\Domain\Adapter\HotelAdapterInterface;
use Module\Integration\Traveline\Domain\Exception\HotelNotConnectedException;
use Module\Integration\Traveline\Domain\Repository\HotelRepositoryInterface;

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
