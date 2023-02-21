<?php

namespace Module\Integration\Traveline\Domain\Adapter;

interface TravelineAdapterInterface
{
    public function sendReservationNotification(): void;
}
