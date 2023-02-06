<?php

namespace GTS\Services\Integration\Traveline\Application\Command;

use GTS\Services\Integration\Traveline\Domain\Adapter\ReservationAdapterInterface;
use GTS\Shared\Application\Command\CommandHandlerInterface;
use GTS\Shared\Application\Command\CommandInterface;

class ConfirmReservationsHandler implements CommandHandlerInterface
{
    public function __construct(private ReservationAdapterInterface $adapter) {}

    /**
     * @param CommandInterface|ConfirmReservations $command
     * @return void
     */
    public function handle(CommandInterface $command)
    {
        $this->adapter->confirmReservation();
    }
}
