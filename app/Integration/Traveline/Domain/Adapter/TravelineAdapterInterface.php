<?php

namespace GTS\Integration\Traveline\Domain\Adapter;

interface TravelineAdapterInterface
{
    public function sendReservationNotification(): void;
}
