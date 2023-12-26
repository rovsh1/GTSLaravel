<?php

namespace Module\Booking\Requesting\Domain\Service\ChangesRegistrar;

use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Booking\IntegrationEvent\HotelBooking\PeriodChanged;
use Sdk\Module\Contracts\Support\ContainerInterface;

class RegistrarFactory
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {}

    public function build(BookingEventInterface $event): ?RegistrarInterface
    {
        $class = $this->getClass($event::class);

        return $class ? $this->container->make($class) : null;
    }

    private function getClass(string $event): ?string
    {
        return match ($event) {
            PeriodChanged::class => PeriodChangedRegistrar::class,
            default => null
        };
    }
}