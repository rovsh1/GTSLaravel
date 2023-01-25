<?php

namespace GTS\Services\Traveline\Application\Command;

use GTS\Services\Traveline\Infrastructure\Adapter\Hotel\AdapterInterface;
use GTS\Shared\Application\Command\CommandHandlerInterface;
use GTS\Shared\Application\Command\CommandInterface;

class GetRoomsAndRatePlansHandler implements CommandHandlerInterface
{

    public function __construct(private AdapterInterface $adapter)
    {
    }


    public function handle(CommandInterface $command)
    {

        //@todo как получать айди отеля
        return $this->adapter->getRoomsAndRatePlans($command->hotelId);
    }

}
