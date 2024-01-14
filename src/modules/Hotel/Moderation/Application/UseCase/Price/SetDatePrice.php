<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\UseCase\Price;

use Carbon\CarbonInterface;
use Module\Hotel\Moderation\Application\Service\PriceSetterHelper;
use Module\Hotel\Moderation\Infrastructure\Models\DatePrice;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetDatePrice implements UseCaseInterface
{
    public function __construct(
        private readonly PriceSetterHelper $priceSetterHelper
    ) {
    }

    public function execute(
        CarbonInterface $date,
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

        DatePrice::updateOrCreate(
            [
                'group_id' => $group->id,
                'season_id' => $seasonId,
                'room_id' => $roomId,
                'date' => $date
            ],
            [
                'price' => $price,
            ]
        );
    }
}
