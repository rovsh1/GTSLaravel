<?php

namespace GTS\Hotel\Infrastructure\Facade;

use Carbon\CarbonPeriod;
use Custom\Framework\Contracts\Bus\CommandBusInterface;
use GTS\Hotel\Application\Command\UpdateRoomPrice;

class RoomPriceFacade implements RoomPriceFacadeInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {}

    public function updateRoomPrice(int $roomId, CarbonPeriod $period, int $rateId, float $price, string $currencyCode)
    {
        return $this->commandBus->execute(new UpdateRoomPrice($roomId, $period, $rateId, $price, $currencyCode));
    }
}
