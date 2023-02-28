<?php

namespace Module\Hotel\Application\Service;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Module\Hotel\Domain\Entity\Season;
use Module\Hotel\Domain\Repository\RoomPriceRepositoryInterface;
use Module\Hotel\Domain\Repository\SeasonRepositoryInterface;

class RoomPriceUpdater
{
    public function __construct(
        private readonly RoomPriceRepositoryInterface $roomPriceRepository,
        private readonly SeasonRepositoryInterface    $seasonRepository,
        private array                                 $hotelSeasons = [],
    ) {}

    public function updateRoomPriceByPeriod(int $roomId, CarbonPeriod $period, int $rateId, int $guestsNumber, bool $isResident, float $price, string $currencyCode)
    {
        if ($currencyCode !== env('DEFAULT_CURRENCY_CODE')) {
            //@todo конвертация валюты + уточнить у Анвара требования
            \Log::warning('Currency not supported', ['currencyCode' => $currencyCode, 'room_id' => $roomId, 'rate_id' => $rateId, 'guests_number' => $guestsNumber]);
            return;
        }

        $this->hotelSeasons = $this->seasonRepository->getActiveSeasonsByRoomIdIncludesPeriod($roomId, $period);
        if (count($this->hotelSeasons) === 0) {
            \Log::warning('Not found hotel season for period', ['period' => $period]);
            return;
        }

        foreach ($period as $date) {
            $seasonId = $this->getSeasonIdByDate($date);
            if ($seasonId === null) {
                \Log::warning("Not found season for date: {$date}", ['date' => $date]);
                continue;
            }
            $this->roomPriceRepository->updateRoomPrices(
                $roomId,
                $seasonId,
                $rateId,
                $guestsNumber,
                $isResident,
                $date,
                $price
            );
        }
    }

    private function getSeasonIdByDate(CarbonInterface $date): ?int
    {
        /** @var Season|null $season */
        $season = \Arr::first($this->hotelSeasons, fn(Season $season) => $date->between(
            $season->period->getStartDate(),
            $season->period->getEndDate(),
        ));

        return $season?->id;
    }
}
