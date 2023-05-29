<?php

namespace Module\Booking\Hotel\Application\Command;

use Module\Booking\Hotel\Application\Query\Find;
use Module\Booking\Hotel\Domain\Entity\DocumentsService;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

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
