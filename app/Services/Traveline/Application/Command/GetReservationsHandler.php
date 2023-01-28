<?php

namespace GTS\Services\Traveline\Application\Command;

use GTS\Services\Traveline\Domain\Entity\Reservation;
use GTS\Services\Traveline\Infrastructure\Adapter\Reservation\AdapterInterface;
use GTS\Shared\Application\Command\CommandHandlerInterface;
use GTS\Shared\Application\Command\CommandInterface;

class GetReservationsHandler implements CommandHandlerInterface
{
    public function __construct(private AdapterInterface $adapter) {}

    /**
     * @param CommandInterface|GetReservations $command
     * @return Reservation[]
     */
    public function handle(CommandInterface $command)
    {
        return $this->adapter->getReservations($command->reservationId, $command->hotelId, $command->startDate);
    }
}
