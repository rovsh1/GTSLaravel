<?php

namespace GTS\Services\Traveline\Domain\Adapter;

interface TravelineAdapterInterface
{
    public function sendReservationNotification(): void;
}
