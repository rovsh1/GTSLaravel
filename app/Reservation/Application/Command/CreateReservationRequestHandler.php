<?php

namespace GTS\Reservation\Application\Command;

use GTS\Reservation\Application\Query\FindReservationByType;
use GTS\Reservation\Domain\Event\ReservationRequestSent;
use GTS\Reservation\Domain\Service\DocumentGenerator\DocumentFactoryInterface;
use GTS\Reservation\Domain\Service\DocumentGenerator\HotelReservation\ReservationRequestGenerator;
use GTS\Reservation\Domain\Service\EventSourcingInterface;
use GTS\Reservation\Domain\ValueObject\ReservationRequestTypeEnum;
use GTS\Reservation\Infrastructure\Repository\ReservationRequestRepository;
use GTS\Shared\Application\Command\CommandHandlerInterface;
use GTS\Shared\Application\Command\CommandInterface;
use GTS\Shared\Domain\Exception\EntityNotFoundException;
use GTS\Shared\Infrastructure\Bus\Port\GatewayInterface;

class CreateReservationRequestHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly ReservationRequestRepository $requestRepository,
        private readonly DocumentFactoryInterface $documentFactory,
        private readonly GatewayInterface $portGateway,
        private readonly EventSourcingInterface $eventSourcing
    ) {}

    public function handle(CommandInterface $command)
    {
        $reservation = $this->queryBus->execute(new FindReservationByType($command->reservationId, $command->reservationType));
        if (!$reservation)
            throw new EntityNotFoundException('Reservation not found');

        $this->isRequestAvailable($reservation);

        $generator = new ReservationRequestGenerator($this->documentFactory);
        $requestFile = $generator->generate($reservation);

        $request = $this->requestRepository->create(
            $reservation->id(),
            $command->reservationType,
            ReservationRequestTypeEnum::RESERVATION->value,
            $requestFile->guid()
        );

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
