<?php

namespace Supplier\Traveline\Domain\Adapter;

interface TravelineAdapterInterface
{
    public function sendReservationNotification(): void;
}
