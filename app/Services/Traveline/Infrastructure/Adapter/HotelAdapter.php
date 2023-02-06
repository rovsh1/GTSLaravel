<?php

namespace GTS\Services\Traveline\Infrastructure\Adapter;

use GTS\Services\Traveline\Domain\Adapter\HotelAdapterInterface;
use GTS\Shared\Domain\Port\PortGatewayInterface;

class HotelAdapter implements HotelAdapterInterface
{
    public function __construct(private PortGatewayInterface $portGateway) {}

    public function getRoomsAndRatePlans(int $hotelId)
    {
        $hotelRequest = new \PortGateway\Request\Hotel\Info\FindByIdRequest($hotelId);
        $hotelDto = $this->portGateway->call($hotelRequest);
        $roomsRequest = new \PortGateway\Request\Hotel\Info\GetRoomsWithPriceRatesByHotelIdRequest($hotelId);
        $roomsDto = $this->portGateway->call($hotelRequest);
        dd($roomsDto);
        return $hotelDto;
    }

    public function updateQuotasAndPlans(): void
    {
        // TODO: Implement updateQuotasAndPlans() method.
    }
}
