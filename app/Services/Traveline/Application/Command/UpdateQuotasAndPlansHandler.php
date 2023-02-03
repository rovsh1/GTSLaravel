<?php

namespace GTS\Services\Traveline\Application\Command;

use GTS\Services\Traveline\Domain\Adapter\HotelAdapterInterface;
use GTS\Shared\Application\Command\CommandHandlerInterface;
use GTS\Shared\Application\Command\CommandInterface;

class UpdateQuotasAndPlansHandler implements CommandHandlerInterface
{
    public function __construct(private HotelAdapterInterface $adapter) {}

    /**
     * @param CommandInterface|UpdateQuotasAndPlans $command
     * @return void
     */
    public function handle(CommandInterface $command)
    {
        $this->adapter->updateQuotasAndPlans();
    }
}
