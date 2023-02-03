<?php

namespace GTS\Services\Traveline\Infrastructure\Facade\Hotel;

use GTS\Services\Traveline\Application\Command\UpdateQuotasAndPlans;
use GTS\Shared\Application\Command\CommandBusInterface;
use GTS\Services\Traveline\Domain\Adapter\Hotel\AdapterInterface;

class Facade implements FacadeInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private AdapterInterface $adapter
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
