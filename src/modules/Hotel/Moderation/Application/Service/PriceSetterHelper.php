<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Service;

use App\Admin\Models\Hotel\Room;
use Module\Hotel\Moderation\Domain\Hotel\Exception\Room\PriceRateNotFound;
use Module\Hotel\Moderation\Domain\Hotel\Exception\Room\RoomNotFound;
use Module\Hotel\Moderation\Infrastructure\Models\Price\Group;
use Module\Hotel\Moderation\Infrastructure\Models\PriceRate;
use Module\Hotel\Moderation\Infrastructure\Models\Season;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

class PriceSetterHelper
{
    public function ensureRoomExists(int $roomId): void
    {
        $room = Room::find($roomId);
        if ($room === null) {
            throw new RoomNotFound('Room not found');
        }
    }

    public function ensureSeasonExists(int $seasonId): void
    {
        $season = Season::find($seasonId);
        if ($season === null) {
            throw new EntityNotFoundException('Season not found');
        }
    }

    public function ensureRateExists(int $rateId): void
    {
        $rate = PriceRate::find($rateId);
        if ($rate === null) {
            throw new PriceRateNotFound('Rate not found');
        }
    }

    public function groupFactory(int $rateId, int $guestsCount, bool $isResident): Group
    {
        $group = Group::whereRateId($rateId)
            ->whereGuestsCount($guestsCount)
            ->whereIsResident($isResident)
            ->first();
        if ($group !== null) {
            return $group;
        }

        return Group::create([
            'rate_id' => $rateId,
            'guests_count' => $guestsCount,
            'is_resident' => $isResident,
        ]);
    }
}
