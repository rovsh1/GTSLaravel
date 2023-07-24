<?php

namespace Module\Hotel\Application\Command\Room\Quota;

use Module\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class CloseHandler implements CommandHandlerInterface
{
    public function __construct(
        private RoomQuotaRepositoryInterface $quotaRepository
    ) {}

    public function handle(CommandInterface|Close $command): void
    {
        $this->quotaRepository->closeRoomQuota($command->roomId, $command->period);
    }
}