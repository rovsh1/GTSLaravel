<?php

namespace GTS\Integration\Traveline\Infrastructure\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;

use GTS\Integration\Traveline\Application\Dto\HotelDto;
use GTS\Integration\Traveline\Application\Dto\RoomDto;
use GTS\Integration\Traveline\Application\Query\GetHotelRoomsAndRatePlans;
use GTS\Integration\Traveline\Domain\Adapter\HotelAdapterInterface;
use GTS\Integration\Traveline\Domain\Exception\HotelNotConnectedException;
use GTS\Integration\Traveline\Infrastructure\Models\TravelineHotel;

class GetHotelRoomsAndRatePlansHandler implements QueryHandlerInterface
{
    public function __construct(
        private HotelAdapterInterface $adapter
    ) {}

    /**
     * @param GetHotelRoomsAndRatePlans $query
     * @return array|null
     */
    public function handle(QueryInterface $query): HotelDto
    {
        $isHotelIntegrationEnabled = TravelineHotel::whereHotelId($query->hotelId)->exists();
        if (!$isHotelIntegrationEnabled) {
            throw new HotelNotConnectedException();
        }

        $hotel = $this->adapter->getHotelById($query->hotelId);
        $rooms = $this->adapter->getRoomsAndRatePlans($query->hotelId);

        $roomsDto = RoomDto::collectionFromHotelRooms($rooms);
        return HotelDto::fromHotel($hotel, $roomsDto);
    }
}
