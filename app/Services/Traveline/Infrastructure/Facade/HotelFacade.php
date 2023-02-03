<?php

namespace GTS\Services\Traveline\Infrastructure\Facade;

use GTS\Services\Traveline\Application\Command\UpdateQuotasAndPlans;
use GTS\Services\Traveline\Domain\Adapter\HotelAdapterInterface;
use GTS\Shared\Application\Command\CommandBusInterface;

class HotelFacade implements HotelFacadeInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private HotelAdapterInterface $adapter
    ) {}

    public function getRoomsAndRatePlans(int $hotelId)
    {
        $hotel = $this->adapter->getRoomsAndRatePlans($hotelId);

        return $hotel; //TODO convert to DTO
    }

    public function updateQuotasAndPlans()
    {
        $hotel = $this->commandBus->execute(new UpdateQuotasAndPlans());

        return $hotel; //TODO convert to DTO
    }
}
