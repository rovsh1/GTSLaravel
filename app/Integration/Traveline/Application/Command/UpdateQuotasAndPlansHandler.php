<?php

namespace GTS\Integration\Traveline\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;

use GTS\Integration\Traveline\Domain\Api\Service\QuotaUpdater;

class UpdateQuotasAndPlansHandler implements CommandHandlerInterface
{
    public function __construct(private QuotaUpdater $updaterService) {}

    /**
     * @param UpdateQuotasAndPlans $command
     * @return void
     */
    public function handle(CommandInterface $command)
    {
        $this->updaterService->updateQuotasAndPlans($command->hotelId, $command->updates);
    }
}
