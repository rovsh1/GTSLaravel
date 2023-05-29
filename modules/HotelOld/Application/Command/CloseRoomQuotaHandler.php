<?php

namespace Module\HotelOld\Application\Command;

use Module\HotelOld\Domain\Repository\RoomQuotaRepositoryInterface;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class CloseRoomQuotaHandler implements CommandHandlerInterface
{
    public function __construct(
        private RoomQuotaRepositoryInterface $quotaRepository
    ) {}

    public function handle(CommandInterface|CloseRoomQuota $command): void
    {
        $this->quotaRepository->closeRoomQuota($command->roomId, $command->period);
    }
}
