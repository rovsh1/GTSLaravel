<?php

namespace GTS\Services\Integration\Traveline\Domain\Adapter;

interface TravelineAdapterInterface
{
    public function sendReservationNotification(): void;
}
