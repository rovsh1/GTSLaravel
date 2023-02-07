<?php

namespace GTS\Integration\Traveline\Application\Command;

use Custom\Framework\Bus\CommandHandlerInterface;
use Custom\Framework\Bus\CommandInterface;
use GTS\Integration\Traveline\Domain\Adapter\HotelAdapterInterface;

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
