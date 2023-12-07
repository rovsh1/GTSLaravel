<?php

namespace Supplier\Traveline\Port\Controllers;

use Sdk\Module\PortGateway\Request;
use Supplier\Traveline\Application\Service\Booking;
use Supplier\Traveline\Application\Service\ReservationFinder;
use Supplier\Traveline\Domain\Api\Response\EmptySuccessResponse;
use Supplier\Traveline\Domain\Api\Response\ErrorResponse;
use Supplier\Traveline\Domain\Api\Response\GetReservationsActionResponse;
use Supplier\Traveline\Domain\Api\Response\HotelNotConnectedToChannelManagerResponse;
use Supplier\Traveline\Domain\Exception\HotelNotConnectedException;

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
            'reservations.*.externalNumber' => 'required|numeric',
            'reservations.*.status' => 'required|string',
        ]);

        $errors = $this->bookingService->confirmReservations($request->reservations);
        if (empty($errors)) {
            return new EmptySuccessResponse();
        }
        return new ErrorResponse($errors);
    }

}