<?php

namespace GTS\Hotel\Infrastructure\Facade;

interface ReservationsFacadeInterface
{
    public function getActiveReservations(): array;
}
