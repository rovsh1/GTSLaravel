<?php

namespace Pkg\Supplier\Traveline\Http\Actions;

use Pkg\Supplier\Traveline\Http\Requests\ConfirmBookingsActionRequest;
use Sdk\Module\Contracts\PortGateway\PortGatewayInterface;

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
