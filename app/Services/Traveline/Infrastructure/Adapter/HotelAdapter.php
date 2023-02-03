<?php

namespace GTS\Services\Traveline\Infrastructure\Adapter;

use GTS\Services\Traveline\Domain\Adapter\HotelAdapterInterface;
use GTS\Shared\Infrastructure\Bus\Port\GatewayInterface;

class HotelAdapter implements HotelAdapterInterface
{
    public function __construct(private GatewayInterface $portGateway) {}

    public function getRoomsAndRatePlans(int $hotelId)
    {
        $hotelDto = $this->portGateway->call($hotelId);
        $roomsDto = $this->portGateway->call($hotelId);
        dd($roomsDto);
        return $hotelDto;
    }

    public function updateQuotasAndPlans(): void
    {
        // TODO: Implement updateQuotasAndPlans() method.
    }
}
