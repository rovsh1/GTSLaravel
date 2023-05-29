<?php

namespace Module\HotelOld\Application\Command;

use Module\HotelOld\Domain\Repository\RoomQuotaRepositoryInterface;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class OpenRoomQuotaHandler implements CommandHandlerInterface
{
    public function __construct(
        private RoomQuotaRepositoryInterface $quotaRepository
    ) {}

    public function handle(CommandInterface|OpenRoomQuota $command): void
    {
        $this->quotaRepository->openRoomQuota($command->roomId, $command->period);
    }
}
