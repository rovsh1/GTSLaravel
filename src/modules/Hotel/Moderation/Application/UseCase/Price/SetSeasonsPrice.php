<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\UseCase\Price;

use Module\Hotel\Moderation\Application\Service\PriceSetterHelper;
use Module\Hotel\Moderation\Infrastructure\Models\DatePrice;
use Module\Hotel\Moderation\Infrastructure\Models\SeasonPrice;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetSeasonsPrice implements UseCaseInterface
{
    public function __construct(
        private readonly PriceSetterHelper $priceSetterHelper
    ) {
    }

    public function execute(
        int $seasonId,
        int $roomId,
        int $rateId,
        int $guestsCount,
        bool $isResident,
        ?float $price
    ): void {
        $this->priceSetterHelper->ensureRoomExists($roomId);
        $this->priceSetterHelper->ensureSeasonExists($seasonId);
        $this->priceSetterHelper->ensureRateExists($rateId);

        $group = $this->priceSetterHelper->groupFactory($rateId, $guestsCount, $isResident);

        if ($price !== null) {
            SeasonPrice::updateOrCreate(
                ['group_id' => $group->id, 'season_id' => $seasonId, 'room_id' => $roomId],
                [
                    'group_id' => $group->id,
                    'season_id' => $seasonId,
                    'room_id' => $roomId,
                    'price' => $price,
                ]
            );
        } else {
            SeasonPrice::whereSeasonId($seasonId)
                ->whereRoomId($roomId)
                ->whereGroupId($group->id)
                ->delete();
        }

        DatePrice::whereSeasonId($seasonId)
            ->whereRoomId($roomId)
            ->whereGroupId($group->id)
            ->delete();
    }
}
