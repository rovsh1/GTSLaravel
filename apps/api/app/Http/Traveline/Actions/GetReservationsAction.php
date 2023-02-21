<?php

namespace App\Api\Http\Traveline\Actions;

use App\Api\Http\Traveline\Requests\GetReservationsActionRequest;
use Module\Integration\Traveline\Domain\Api\Response\GetReservationsActionResponse;
use Module\Integration\Traveline\Domain\Api\Response\HotelNotConnectedToChannelManagerResponse;
use Module\Integration\Traveline\Domain\Exception\HotelNotConnectedException;
use Module\Shared\Infrastructure\Adapter\PortGatewayInterface;

class GetReservationsAction
{
    public function __construct(private PortGatewayInterface $portGateway) {}

    public function handle(GetReservationsActionRequest $request)
    {
        try {
            $reservations = $this->portGateway->request('traveline/getReservations', [
                'reservation_id' => $request->getReservationId(),
                'hotel_id' => $request->getHotelId(),
                'date_update' => $request->getStartTime(),
            ]);
        } catch (HotelNotConnectedException $exception) {
            return new HotelNotConnectedToChannelManagerResponse();
        }
        return new GetReservationsActionResponse($reservations);
    }

}
