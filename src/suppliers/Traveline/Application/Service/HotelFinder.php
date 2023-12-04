<?php

namespace Supplier\Traveline\Application\Service;

use Supplier\Traveline\Application\Dto\HotelDto;
use Supplier\Traveline\Application\Dto\RoomDto;
use Supplier\Traveline\Domain\Adapter\HotelAdapterInterface;
use Supplier\Traveline\Domain\Exception\HotelNotConnectedException;
use Supplier\Traveline\Domain\Repository\HotelRepositoryInterface;
use Supplier\Traveline\Domain\Service\HotelRoomCodeGeneratorInterface;

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
