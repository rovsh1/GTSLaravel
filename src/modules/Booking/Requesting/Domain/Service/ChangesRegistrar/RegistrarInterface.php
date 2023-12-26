<?php

namespace Module\Booking\Requesting\Domain\Service\ChangesRegistrar;

use Sdk\Booking\IntegrationEvent\BookingEventInterface;

interface RegistrarInterface
{
    public function register(BookingEventInterface $event): void;
}