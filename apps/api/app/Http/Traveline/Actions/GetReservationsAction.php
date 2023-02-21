<?php

namespace App\Api\Http\Traveline\Actions;

use App\Api\Http\Traveline\Requests\GetReservationsActionRequest;
use Module\Shared\Infrastructure\Adapter\PortGatewayInterface;

class GetReservationsAction
{
    public function __construct(private PortGatewayInterface $portGateway) {}

    public function handle(GetReservationsActionRequest $request)
    {
        return $this->portGateway->request('traveline/getReservations', [
            'reservation_id' => $request->getReservationId(),
            'hotel_id' => $request->getHotelId(),
            'date_update' => $request->getStartTime(),
        ]);
    }

}
