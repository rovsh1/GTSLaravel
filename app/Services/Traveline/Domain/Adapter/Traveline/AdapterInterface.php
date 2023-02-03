<?php

namespace GTS\Services\Traveline\Domain\Adapter\Traveline;

interface AdapterInterface
{
    public function sendReservationNotification(): void;
}
