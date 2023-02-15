<?php

namespace GTS\Hotel\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;

use GTS\Hotel\Domain\Repository\RoomPriceRepositoryInterface;

class UpdateRoomPriceHandler implements CommandHandlerInterface
{
    public function __construct(
        private RoomPriceRepositoryInterface $roomPriceRepository
    ) {}

    /**
     * @param UpdateRoomPrice $command
     * @return void
     */
    public function handle(CommandInterface $command)
    {
        $this->roomPriceRepository->updateRoomPrice($command->roomId, $command->priceRateId, $command->price);
    }
}
