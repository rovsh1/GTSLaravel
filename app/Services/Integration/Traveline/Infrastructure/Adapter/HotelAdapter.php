<?php

namespace GTS\Services\Integration\Traveline\Infrastructure\Adapter;

use GTS\Services\Integration\Traveline\Domain\Adapter\HotelAdapterInterface;
use GTS\Shared\Domain\Port\PortGatewayInterface;

class HotelAdapter implements HotelAdapterInterface
{
    public function __construct(private PortGatewayInterface $portGateway) {}

    public function getHotelById(int $hotelId)
    {
        $hotelRequest = new \PortGateway\Hotel\Info\FindByIdRequest($hotelId);
        return $this->portGateway->call($hotelRequest);
    }

    public function getRoomsAndRatePlans(int $hotelId)
    {
        $roomsRequest = new \PortGateway\Hotel\Info\GetRoomsWithPriceRatesByHotelIdRequest($hotelId);
        return $this->portGateway->call($roomsRequest);
    }

    public function updateQuotasAndPlans(): void
    {
        // TODO: Implement updateQuotasAndPlans() method.
    }
}
