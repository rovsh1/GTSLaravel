<?php

namespace Module\Hotel\Moderation\Application\Command\Price\Date;

use App\Admin\Models\Hotel\Room;
use Module\Hotel\Moderation\Domain\Hotel\Exception\Room\PriceRateNotFound;
use Module\Hotel\Moderation\Domain\Hotel\Exception\Room\RoomNotFound;
use Module\Hotel\Moderation\Infrastructure\Models\DatePrice;
use Module\Hotel\Moderation\Infrastructure\Models\Price\Group;
use Module\Hotel\Moderation\Infrastructure\Models\Room\PriceRate;
use Module\Hotel\Moderation\Infrastructure\Models\Season;
use Sdk\Module\Contracts\Bus\CommandHandlerInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class SetHandler implements CommandHandlerInterface
{
    public function __construct() {}

    public function handle(CommandInterface|Set $command): bool
    {
        $room = Room::find($command->roomId);
        if ($room === null) {
            throw new RoomNotFound('Room not found');
        }
        $season = Season::find($command->seasonId);
        if ($season === null) {
            throw new EntityNotFoundException('Season not found');
        }
        $rate = PriceRate::find($command->rateId);
        if ($rate === null) {
            throw new PriceRateNotFound('Rate not found');
        }

        $group = Group::whereRateId($command->rateId)
            ->whereGuestsCount($command->guestsCount)
            ->whereIsResident($command->isResident)
            ->first();
        if ($group === null) {
            $group = Group::create([
                'rate_id' => $command->rateId,
                'guests_count' => $command->guestsCount,
                'is_resident' => $command->isResident,
            ]);
        }

        DatePrice::updateOrCreate(
            [
                'group_id' => $group->id,
                'season_id' => $command->seasonId,
                'room_id' => $command->roomId,
                'date' => $command->date
            ],
            [
                'date' => $command->date,
                'group_id' => $group->id,
                'season_id' => $command->seasonId,
                'room_id' => $command->roomId,
                'price' => $command->price,
            ]
        );

        return true;
    }
}
