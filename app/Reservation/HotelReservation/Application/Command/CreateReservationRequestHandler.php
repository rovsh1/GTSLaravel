<?php

namespace GTS\Reservation\HotelReservation\Application\Command;

use Custom\Framework\Bus\CommandHandlerInterface;
use Custom\Framework\Bus\CommandInterface;
use Custom\Framework\Bus\QueryBusInterface;
use Custom\Framework\Exception\EntityNotFoundException;
use GTS\Reservation\HotelReservation\Application\Query\Find;
use GTS\Reservation\HotelReservation\Domain\Entity\DocumentsService;

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
