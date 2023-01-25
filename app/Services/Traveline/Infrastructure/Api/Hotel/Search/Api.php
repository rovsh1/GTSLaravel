<?php

namespace GTS\Services\Traveline\Infrastructure\Api\Hotel\Search;

use GTS\Services\Traveline\Application\Command\GetRoomsAndRatePlans;
use GTS\Shared\Application\Command\CommandBusInterface;

class Api implements ApiInterface
{

    public function __construct(
        private CommandBusInterface $commandBus
    )
    {
    }

    public function getRoomsAndRatePlans(int $hotelId)
    {
        $hotel = $this->commandBus->execute(new GetRoomsAndRatePlans($hotelId));

        return $hotel; //TODO convert to DTO
    }

}
