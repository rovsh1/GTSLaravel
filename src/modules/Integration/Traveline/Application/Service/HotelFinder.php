<?php

namespace Module\Integration\Traveline\Application\Service;

use Module\Integration\Traveline\Application\Dto\HotelDto;
use Module\Integration\Traveline\Application\Dto\RoomDto;
use Module\Integration\Traveline\Domain\Adapter\HotelAdapterInterface;
use Module\Integration\Traveline\Domain\Exception\HotelNotConnectedException;
use Module\Integration\Traveline\Domain\Repository\HotelRepositoryInterface;
use Module\Integration\Traveline\Domain\Service\HotelRoomCodeGeneratorInterface;

class HotelFinder
{
    public function __construct(
        private readonly HotelAdapterInterface         $adapter,
        private readonly HotelRepositoryInterface      $hotelRepository,
        private readonly HotelRoomCodeGeneratorInterface $codeGenerator
    ) {}

    public function getHotelRoomsAndRatePlans(int $hotelId): HotelDto
    {
        $isHotelIntegrationEnabled = $this->hotelRepository->isHotelIntegrationEnabled($hotelId);
        if (!$isHotelIntegrationEnabled) {
            throw new HotelNotConnectedException();
        }

        $hotel = $this->adapter->getHotelById($hotelId);
        $rooms = $this->adapter->getRoomsAndRatePlans($hotelId);

        $roomsDto = RoomDto::collectionFromHotelRooms($this->codeGenerator, $rooms);
        return HotelDto::fromHotel($hotel, $roomsDto);
    }

}
