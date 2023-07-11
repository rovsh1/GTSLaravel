<?php

namespace Module\Integration\Traveline\Port\Controllers;

use Custom\Framework\Port\Request;
use Module\Integration\Traveline\Application\Service\Booking;
use Module\Integration\Traveline\Application\Service\ReservationFinder;
use Module\Integration\Traveline\Domain\Api\Response\EmptySuccessResponse;
use Module\Integration\Traveline\Domain\Api\Response\ErrorResponse;
use Module\Integration\Traveline\Domain\Api\Response\GetReservationsActionResponse;
use Module\Integration\Traveline\Domain\Api\Response\HotelNotConnectedToChannelManagerResponse;
use Module\Integration\Traveline\Domain\Exception\HotelNotConnectedException;

class ReservationController
{
    public function __construct(
        private Booking $bookingService,
        private ReservationFinder   $reservationFinder
    ) {}

    public function getReservations(Request $request): mixed
    {
        $request->validate([
            'reservation_id' => 'nullable|numeric',
            'hotel_id' => 'nullable|numeric',
            'date_update' => 'nullable|date',
        ]);

        try {
            $reservations = $this->reservationFinder->getReservations(
                $request->reservation_id,
                $request->hotel_id,
                $request->date_update
            );
        } catch (HotelNotConnectedException $exception) {
            return new HotelNotConnectedToChannelManagerResponse();
        }
        return new GetReservationsActionResponse($reservations);
    }

    public function confirmReservations(Request $request): mixed
    {
        $request->validate([
            'reservations' => 'required|array',
            'reservations.*.number' => 'required|numeric',
            'reservations.*.externalNumber' => 'required',
            'reservations.*.status' => 'required|string',
        ]);

        $errors = $this->bookingService->confirmReservations($request->reservations);
        if (empty($errors)) {
            return new EmptySuccessResponse();
        }
        return new ErrorResponse($errors);
    }

}
