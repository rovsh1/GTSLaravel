<?php

namespace GTS\Integration\Traveline\Infrastructure\Facade;

use Custom\Framework\Contracts\Bus\CommandBusInterface;
use Custom\Framework\Contracts\Bus\QueryBusInterface;

use GTS\Integration\Traveline\Application\Command\UpdateQuotasAndPlans;
use GTS\Integration\Traveline\Application\Dto\HotelDto;
use GTS\Integration\Traveline\Application\Query\GetHotelRoomsAndRatePlans;

class HotelFacade implements HotelFacadeInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface   $queryBus
    ) {}

    public function getRoomsAndRatePlans(int $hotelId): HotelDto
    {
        return $this->queryBus->execute(new GetHotelRoomsAndRatePlans($hotelId));
    }

    public function updateQuotasAndPlans()
    {
        $hotel = $this->commandBus->execute(new UpdateQuotasAndPlans());

        return $hotel; //TODO convert to DTO
    }
}
