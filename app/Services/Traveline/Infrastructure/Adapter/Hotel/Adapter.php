<?php

namespace GTS\Services\Traveline\Infrastructure\Adapter\Hotel;

use GTS\Services\Traveline\Domain\Adapter\Hotel\AdapterInterface;
use GTS\Shared\Infrastructure\Bus\PortGatewayInterface;

class Adapter implements AdapterInterface
{
    public function __construct(private PortGatewayInterface $portGateway) {}

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
