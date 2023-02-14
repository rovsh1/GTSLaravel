<?php

namespace GTS\Hotel\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use GTS\Hotel\Domain\Repository\QuotaRepositoryInterface;

class ReserveQuotaHandler implements CommandHandlerInterface
{
    public function __construct(
        private QuotaRepositoryInterface $quotaRepository
    ) {}

    /**
     * @param ReserveQuota $command
     * @return void
     */
    public function handle(CommandInterface $command)
    {
        $this->quotaRepository->reserveRoomQuotaByDate($command->roomId, $command->date, $command->count);
    }
}
