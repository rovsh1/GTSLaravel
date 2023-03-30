<?php

namespace Module\Hotel\Application\Command;

use App\Admin\Models\Hotel\Room;
use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;

class SetSeasonPriceHandler implements CommandHandlerInterface
{
    public function __construct() {}

    public function handle(CommandInterface|SetSeasonPrice $command): bool
    {
        $i = 1;
        foreach ($command->sortedRoomsIds as $id) {
            $room = Room::find($id);
            if (!$room) {
                throw new \Exception('Room not found', 404);
            }

            $room->update(['position' => $i++]);
        }

        return true;
    }
}
