<?php

namespace App\Api\Http\Actions;

use App\Api\Http\Requests\GetReservationsActionRequest;
use Module\Integration\Traveline\Domain\Api\Response\GetReservationsActionResponse;
use Module\Integration\Traveline\Domain\Api\Response\HotelNotConnectedToChannelManagerResponse;
use Module\Integration\Traveline\Domain\Exception\HotelNotConnectedException;
use Module\Integration\Traveline\Infrastructure\Facade\ReservationFacadeInterface;

class GetReservationsAction
{

    public function __construct(private ReservationFacadeInterface $facade) {}

    public function handle(GetReservationsActionRequest $request)
    {
        try {
            $reservations = $this->facade->getReservations($request->getReservationId(), $request->getHotelId(), $request->getStartTime());
        } catch (HotelNotConnectedException $exception) {
            return new HotelNotConnectedToChannelManagerResponse();
        }
        return new GetReservationsActionResponse($reservations);
    }

}
