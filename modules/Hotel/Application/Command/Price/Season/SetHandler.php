<?php

namespace Module\Hotel\Application\Command\Price\Season;

use App\Admin\Models\Hotel\Room;
use Module\Hotel\Domain\Exception\Room\PriceRateNotFound;
use Module\Hotel\Domain\Exception\Room\RoomNotFound;
use Module\Hotel\Infrastructure\Models\DatePrice;
use Module\Hotel\Infrastructure\Models\Price\Group;
use Module\Hotel\Infrastructure\Models\Room\PriceRate;
use Module\Hotel\Infrastructure\Models\Season;
use Module\Hotel\Infrastructure\Models\SeasonPrice;
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
            ->whereGuestsNumber($command->guestsNumber)
            ->whereIsResident($command->isResident)
            ->first();
        if ($group === null) {
            $group = Group::create([
                'rate_id' => $command->rateId,
                'guests_number' => $command->guestsNumber,
                'is_resident' => $command->isResident,
            ]);
        }

        SeasonPrice::updateOrCreate(
            ['group_id' => $group->id, 'season_id' => $command->seasonId, 'room_id' => $command->roomId],
            [
                'group_id' => $group->id,
                'season_id' => $command->seasonId,
                'room_id' => $command->roomId,
                'price' => $command->price,
                'currency_id' => $command->currencyId,
            ]
        );

        DatePrice::whereSeasonId($command->seasonId)
            ->whereRoomId($command->roomId)
            ->whereGroupId($group->id)
            ->delete();

        return true;
    }
}
