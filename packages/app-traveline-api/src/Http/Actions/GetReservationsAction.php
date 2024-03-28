<?php

namespace Pkg\App\Traveline\Http\Actions;

use Pkg\App\Traveline\Http\Request\GetReservationsActionRequest;
use Pkg\App\Traveline\Http\Response\GetReservationsActionResponse;
use Pkg\App\Traveline\Http\Response\HotelNotConnectedToChannelManagerResponse;
use Pkg\Supplier\Traveline\Exception\HotelNotConnectedException;
use Pkg\Supplier\Traveline\UseCase\GetReservations;

class GetReservationsAction
{
    public function handle(GetReservationsActionRequest $request)
    {
        try {
            $reservations = app(GetReservations::class)->execute(
                $request->getReservationId(),
                $request->getHotelId(),
                $request->getStartTime()
            );
        } catch (HotelNotConnectedException) {
            return new HotelNotConnectedToChannelManagerResponse();
        }

        return new GetReservationsActionResponse($reservations);
    }

}
