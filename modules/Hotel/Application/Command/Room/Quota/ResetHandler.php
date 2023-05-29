<?php

namespace Module\Hotel\Application\Command\Room\Quota;

use Module\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class ResetHandler implements CommandHandlerInterface
{
    public function __construct(
        private RoomQuotaRepositoryInterface $quotaRepository
    ) {}

    public function handle(CommandInterface|Reset $command): void
    {
        $this->quotaRepository->resetRoomQuota($command->roomId, $command->period);
    }
}
