<?php

namespace GTS\Reservation\HotelReservation\Application\Command;

use GTS\Reservation\HotelReservation\Application\Query\Find;
use GTS\Reservation\HotelReservation\Domain\Entity\DocumentsService;
use GTS\Shared\Application\Command\CommandHandlerInterface;
use GTS\Shared\Application\Command\CommandInterface;
use GTS\Shared\Application\Query\QueryBusInterface;
use GTS\Shared\Domain\Exception\EntityNotFoundException;

class CreateReservationRequestHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly DocumentsService $documentsService,
    ) {}

    public function handle(CommandInterface $command)
    {
        $reservation = $this->queryBus->execute(new Find($command->id));
        if (!$reservation)
            throw new EntityNotFoundException('Reservation not found');

        return $this->documentsService->sendReservationRequest($reservation);
    }
}
