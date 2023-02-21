<?php

namespace App\Api\Http\Traveline\Actions;

use App\Api\Http\Traveline\Requests\ConfirmBookingsActionRequest;
use Module\Shared\Infrastructure\Adapter\PortGatewayInterface;

class ConfirmBookingsAction
{
    public function __construct(private PortGatewayInterface $portGateway) {}

    public function handle(ConfirmBookingsActionRequest $request)
    {
        return $this->portGateway->request('traveline/confirmReservations', [
            'reservations' => $request->getReservations()
        ]);
    }
}
