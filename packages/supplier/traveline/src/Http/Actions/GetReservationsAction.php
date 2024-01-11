<?php

namespace Pkg\Supplier\Traveline\Http\Actions;

use Pkg\Supplier\Traveline\Http\Requests\GetReservationsActionRequest;
use Sdk\Module\Contracts\PortGateway\PortGatewayInterface;

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
