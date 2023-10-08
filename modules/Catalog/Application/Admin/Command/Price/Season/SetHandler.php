<?php

namespace Module\Catalog\Application\Admin\Command\Price\Season;

use App\Admin\Models\Hotel\Room;
use Module\Catalog\Domain\Hotel\Exception\Room\PriceRateNotFound;
use Module\Catalog\Domain\Hotel\Exception\Room\RoomNotFound;
use Module\Catalog\Infrastructure\Models\DatePrice;
use Module\Catalog\Infrastructure\Models\Price\Group;
use Module\Catalog\Infrastructure\Models\Room\PriceRate;
use Module\Catalog\Infrastructure\Models\Season;
use Module\Catalog\Infrastructure\Models\SeasonPrice;
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

        if ($command->price !== null) {
            SeasonPrice::updateOrCreate(
                ['group_id' => $group->id, 'season_id' => $command->seasonId, 'room_id' => $command->roomId],
                [
                    'group_id' => $group->id,
                    'season_id' => $command->seasonId,
                    'room_id' => $command->roomId,
                    'price' => $command->price,
                ]
            );
        } else {
            SeasonPrice::whereSeasonId($command->seasonId)
                ->whereRoomId($command->roomId)
                ->whereGroupId($group->id)
                ->delete();
        }

        DatePrice::whereSeasonId($command->seasonId)
            ->whereRoomId($command->roomId)
            ->whereGroupId($group->id)
            ->delete();

        return true;
    }
}
