<?php

namespace Module\Integration\Traveline\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Integration\Traveline\Domain\Api\Service\Booking;

class ConfirmReservationsHandler implements CommandHandlerInterface
{
    public function __construct(private Booking $bookingService) {}

    /**
     * @param ConfirmReservations $command
     * @return void
     */
    public function handle(CommandInterface $command)
    {
        $this->bookingService->confirmReservations($command->reservations);
    }
}
