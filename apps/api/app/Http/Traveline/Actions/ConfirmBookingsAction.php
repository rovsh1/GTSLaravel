<?php

namespace App\Api\Http\Traveline\Actions;

use App\Api\Http\Traveline\Requests\ConfirmBookingsActionRequest;
use Module\Integration\Traveline\Domain\Api\Response\EmptySuccessResponse;
use Module\Shared\Infrastructure\Adapter\PortGatewayInterface;

class ConfirmBookingsAction
{
    public function __construct(private PortGatewayInterface $portGateway) {}

    public function handle(ConfirmBookingsActionRequest $request)
    {
        $this->portGateway->request('traveline/confirmReservations', [
            'reservations' => $request->getReservations()
        ]);
        return new EmptySuccessResponse();
    }
}
