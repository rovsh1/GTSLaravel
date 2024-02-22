<?php

namespace Pkg\Supplier\Traveline\Http\Actions;

use Pkg\Supplier\Traveline\Exception\HotelNotConnectedException;
use Pkg\Supplier\Traveline\Http\Request\GetReservationsActionRequest;
use Pkg\Supplier\Traveline\Http\Response\GetReservationsActionResponse;
use Pkg\Supplier\Traveline\Http\Response\HotelNotConnectedToChannelManagerResponse;
use Pkg\Supplier\Traveline\Service\BookingService;

class GetReservationsAction
{
    public function __construct(
        private readonly BookingService $bookingService,
    ) {}

    public function handle(GetReservationsActionRequest $request)
    {
        try {
            $reservations = $this->bookingService->getReservations(
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
