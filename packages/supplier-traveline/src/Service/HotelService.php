<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Service;

use Pkg\Supplier\Traveline\Adapters\HotelAdapter;
use Pkg\Supplier\Traveline\Dto\HotelDto;
use Pkg\Supplier\Traveline\Dto\RoomDto;
use Pkg\Supplier\Traveline\Exception\HotelNotConnectedException;
use Pkg\Supplier\Traveline\Repository\HotelRepository;

class HotelService
{
    public function __construct(
        private readonly HotelRepository $hotelRepository,
        private readonly HotelAdapter $adapter
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
