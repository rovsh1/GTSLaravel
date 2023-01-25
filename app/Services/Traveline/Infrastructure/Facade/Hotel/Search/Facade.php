<?php

namespace GTS\Services\Traveline\Infrastructure\Facade\Hotel\Search;

use GTS\Services\Traveline\Application\Command\GetRoomsAndRatePlans;
use GTS\Shared\Application\Command\CommandBusInterface;

class Facade implements FacadeInterface
{

    public function __construct(
        private CommandBusInterface $commandBus
    )
    {}

    public function getRoomsAndRatePlans(int $hotelId)
    {
        $hotel = $this->commandBus->execute(new GetRoomsAndRatePlans($hotelId));

        return $hotel; //TODO convert to DTO
    }

}
