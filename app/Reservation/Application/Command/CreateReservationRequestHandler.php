<?php

namespace GTS\Reservation\Application\Command;

use GTS\Reservation\Application\Query\FindReservationByType;
use GTS\Reservation\Domain\Event\ReservationRequestSent;
use GTS\Reservation\Domain\Service\DocumentGenerator\DocumentFactoryInterface;
use GTS\Reservation\Domain\Service\DocumentGenerator\ReservationRequestGenerator;
use GTS\Reservation\Domain\Service\EventSourcingInterface;
use GTS\Shared\Application\Command\CommandHandlerInterface;
use GTS\Shared\Application\Command\CommandInterface;
use GTS\Shared\Domain\Exception\EntityNotFoundException;

class CreateReservationRequestHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly DocumentFactoryInterface $documentFactory,
        private readonly EventSourcingInterface $eventSourcing
    ) {}

    public function handle(CommandInterface $command)
    {
        $reservation = $this->queryBus->execute(new FindReservationByType($command->reservationId, $command->reservationType));
        if (!$reservation)
            throw new EntityNotFoundException('Reservation not found');

        $this->isRequestAvailable($reservation);

        $generator = new ReservationRequestGenerator($this->documentFactory);
        $reservationRequest = $generator->generate($reservation);

        $event = new ReservationRequestSent($reservation->id());

        $this->eventSourcing->add($event);

        $this->eventsBus->add($event);
    }

    private function isRequestAvailable($reservation)
    {
        if (!$reservation->canRequest()) //TODO check status
            throw new \Exception('Reservation not found');
    }
}
