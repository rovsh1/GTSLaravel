<?php

namespace GTS\Services\Traveline\Application\Command;

use GTS\Services\Traveline\Infrastructure\Adapter\Hotel\AdapterInterface;
use GTS\Shared\Application\Command\CommandHandlerInterface;
use GTS\Shared\Application\Command\CommandInterface;

class GetRoomsAndRatePlansHandler implements CommandHandlerInterface
{
    public function __construct(private AdapterInterface $adapter) {}

    /**
     * @param CommandInterface|GetRoomsAndRatePlans $command
     * @return
     */
    public function handle(CommandInterface $command)
    {
        return $this->adapter->getRoomsAndRatePlans($command->hotelId);
    }
}
