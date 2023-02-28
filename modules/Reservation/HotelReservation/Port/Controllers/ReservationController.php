<?php

namespace Module\Reservation\HotelReservation\Port\Controllers;

use Custom\Framework\Contracts\Event\DomainEventDispatcherInterface;
use Custom\Framework\Port\Request;
use Module\Reservation\HotelReservation\Domain\Event\ReservationCancelled;

class ReservationController
{
    public function __construct(
        private readonly DomainEventDispatcherInterface $eventDispatcher
    ) {}

    //TODO TEST method
    public function cancel(Request $request)
    {
        $this->eventDispatcher->dispatch(new ReservationCancelled(12, 'Test cancel'));
    }

}
