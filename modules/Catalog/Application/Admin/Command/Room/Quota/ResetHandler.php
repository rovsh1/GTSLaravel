<?php

namespace Module\Catalog\Application\Admin\Command\Room\Quota;

use Module\Catalog\Domain\Hotel\Repository\RoomQuotaRepositoryInterface;
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
