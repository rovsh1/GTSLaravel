<?php

namespace GTS\Services\Traveline\Application\Command;

use GTS\Services\Traveline\Infrastructure\Adapter\Hotel\AdapterInterface;
use GTS\Shared\Application\Command\CommandHandlerInterface;
use GTS\Shared\Application\Command\CommandInterface;

class UpdateQuotasAndPlansHandler implements CommandHandlerInterface
{
    public function __construct(private AdapterInterface $adapter) {}

    /**
     * @param CommandInterface|UpdateQuotasAndPlans $command
     * @return void
     */
    public function handle(CommandInterface $command)
    {
        $this->adapter->updateQuotasAndPlans();
    }
}
