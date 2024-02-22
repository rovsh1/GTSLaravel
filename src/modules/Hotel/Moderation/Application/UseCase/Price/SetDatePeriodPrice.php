<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\UseCase\Price;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;
use Module\Hotel\Moderation\Application\Service\PriceSetterHelper;
use Module\Hotel\Moderation\Domain\Hotel\Exception\Room\PriceRateNotFound;
use Module\Hotel\Moderation\Domain\Hotel\Exception\Room\RoomNotFound;
use Module\Hotel\Moderation\Domain\Hotel\Exception\Room\UnsupportedRoomGuestsNumber;
use Module\Hotel\Moderation\Domain\Hotel\Repository\PriceRateRepositoryInterface;
use Module\Hotel\Moderation\Domain\Hotel\Repository\RoomRepositoryInterface;
use Module\Hotel\Moderation\Infrastructure\Models\DatePrice;
use Module\Hotel\Moderation\Infrastructure\Models\Season;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetDatePeriodPrice implements UseCaseInterface
{
    private array $hotelSeasons = [];

    public function __construct(
        private readonly PriceSetterHelper $priceSetterHelper,
        private readonly RoomRepositoryInterface $roomRepository,
        private readonly PriceRateRepositoryInterface $priceRateRepository,
    ) {}

    public function execute(
        CarbonPeriod $period,
        int $roomId,
        int $rateId,
        int $guestsCount,
        bool $isResident,
        string $currencyCode,
        float $price
    ): void {
        if ($currencyCode !== env('DEFAULT_CURRENCY_CODE')) {
            //Конвертация валюты не требуется, уточнял у Анвара. По договору, отель должен заносить цены в сумах в тревелайн.
            \Log::warning('Currency not supported', ['currencyCode' => $currencyCode, 'room_id' => $roomId, 'rate_id' => $rateId, 'guests_count' => $guestsCount]);
            return;
        }
        $this->priceSetterHelper->ensureRoomExists($roomId);
        $this->priceSetterHelper->ensureRateExists($rateId);
        $this->priceSetterHelper->ensureSeasonForPeriodExists($roomId, $period);
        $this->initHotelSeasons($roomId, $period);

        if (!$this->priceRateRepository->existsByRoomAndRate($roomId, $rateId)) {
            throw new PriceRateNotFound("Price rate with id {$rateId}, not found for room with id {$roomId}");
        }
        $group = $this->priceSetterHelper->groupFactory($rateId, $guestsCount, $isResident);

        $updateData = [];
        foreach ($period as $date) {
            $seasonId = $this->getSeasonIdByDate($date);
            if ($seasonId === null) {
                \Log::warning("Not found season for date: {$date}", ['date' => $date]);
                continue;
            }
            $updateData[] = [
                'room_id' => $roomId,
                'season_id' => $seasonId,
                'group_id' => $group->id,
                'date' => $date->toDateString(),
                'price' => $price
            ];
        }

        DatePrice::upsert($updateData, ['group_id', 'season_id', 'room_id', 'date'], ['price']);
        $this->hotelSeasons = [];
    }

    private function initHotelSeasons(int $roomId, CarbonPeriod $period): void
    {
        $this->hotelSeasons = Season::query()
            ->whereRoomId($roomId)
            ->whereDateStart('<=', $period->getStartDate())
            ->whereDateEnd('>=', $period->getEndDate())
            ->get()
            ->all();
    }

    private function getSeasonIdByDate(CarbonInterface $date): ?int
    {
        /** @var Season|null $season */
        $season = Arr::first($this->hotelSeasons, fn(Season $season) => $date->between(
            $season->period->getStartDate(),
            $season->period->getEndDate(),
        ));

        return $season?->id;
    }
}
