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
                $request->reservation_id,
                $request->hotel_id,
                $request->date_update
            );
        } catch (HotelNotConnectedException $exception) {
            return new HotelNotConnectedToChannelManagerResponse();
        }

        return new GetReservationsActionResponse($reservations);
    }

}
