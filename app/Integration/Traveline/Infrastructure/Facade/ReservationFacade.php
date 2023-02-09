<?php

namespace GTS\Integration\Traveline\Infrastructure\Facade;

use Carbon\CarbonInterface;
use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\Contracts\Bus\QueryBusInterface;
use GTS\Integration\Traveline\Application\Command\ConfirmReservations;
use GTS\Integration\Traveline\Application\Query\GetReservations;
use GTS\Reservation\HotelReservation\Application\Dto\ReservationDto;

class ReservationFacade implements ReservationFacadeInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface   $queryBus
    ) {}

    public function getReservations(?int $id = null, ?int $hotelId = null, ?CarbonInterface $startDate = null): array
    {
        $reservations = $this->queryBus->execute(new GetReservations($id, $hotelId, $startDate));

        return ReservationDto::collection($reservations)->all();
    }

    public function confirmReservations()
    {
        return $this->commandBus->execute(new ConfirmReservations());
    }
}
