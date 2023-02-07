<?php

namespace GTS\Integration\Traveline\Application\Command;

use Custom\Framework\Bus\CommandHandlerInterface;
use Custom\Framework\Bus\CommandInterface;
use GTS\Integration\Traveline\Domain\Adapter\ReservationAdapterInterface;

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
