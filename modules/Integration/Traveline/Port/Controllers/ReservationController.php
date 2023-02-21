<?php

namespace Module\Integration\Traveline\Port\Controllers;

use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\Port\Request;
use Module\Integration\Traveline\Application\Command\ConfirmReservations;
use Module\Integration\Traveline\Application\Service\ReservationFinder;

class ReservationController
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private ReservationFinder   $reservationFinder
    ) {}

    public function getReservations(Request $request): mixed
    {
        $request->validate([
            'reservation_id' => 'nullable|numeric',
            'hotel_id' => 'nullable|numeric',
            'date_update' => 'nullable|date',
        ]);

        return $this->reservationFinder->getReservations(
            $request->id,
            $request->hotel_id,
            $request->date_update
        );
    }

    public function confirmReservations(Request $request): mixed
    {
        $request->validate([
            'reservations' => 'required|array',
            'reservations.*.number' => 'required|numeric',
            'reservations.*.externalNumber' => 'required|numeric',
            'reservations.*.status' => 'required|string',
        ]);

        return $this->commandBus->execute(new ConfirmReservations($request->reservations));
    }

}
