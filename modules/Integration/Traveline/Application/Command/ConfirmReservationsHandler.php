<?php

namespace Module\Integration\Traveline\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Integration\Traveline\Application\Service\Booking;

class ConfirmReservationsHandler implements CommandHandlerInterface
{
    public function __construct(private Booking $bookingService) {}

    public function handle(CommandInterface|ConfirmReservations $command): void
    {
        $this->bookingService->confirmReservations($command->reservations);
    }
}
