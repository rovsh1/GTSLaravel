<?php

namespace Module\Hotel\Moderation\Application\Admin\Command\Room\Quota;

use Module\Hotel\Moderation\Domain\Hotel\Repository\RoomQuotaRepositoryInterface;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class UpdateHandler implements CommandHandlerInterface
{
    public function __construct(
        private RoomQuotaRepositoryInterface $quotaRepository
    ) {}

    public function handle(CommandInterface|Update $command): void
    {
        $this->quotaRepository->updateRoomQuota($command->roomId, $command->period, $command->quota, $command->releaseDays);
    }
}
