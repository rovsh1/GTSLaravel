<?php

namespace GTS\Hotel\Domain\Service;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;

use GTS\Hotel\Domain\Entity\Season;
use GTS\Hotel\Domain\Repository\RoomPriceRepositoryInterface;
use GTS\Hotel\Domain\Repository\SeasonRepositoryInterface;

class RoomPriceUpdater
{
    public function __construct(
        private RoomPriceRepositoryInterface $roomPriceRepository,
        private SeasonRepositoryInterface    $seasonRepository,
        private array                        $hotelSeasons = [],
    ) {}

    public function updateRoomPriceByDate(int $roomId, CarbonPeriod $period, int $rateId, int $guestsNumber, float $price, string $currencyCode)
    {
        //@todo нормальная проверка на валюту и норм. ексепшн
        if ($currencyCode !== 'USZ') {
            throw new \Exception('Currency not supported');
        }
        //@todo получение отеля (видимо по комнате)
        $this->hotelSeasons = $this->seasonRepository->getActiveSeasonsIncludesPeriod(333, $period);
        if (count($this->hotelSeasons) === 0) {
            throw new \Exception('Not found hotel season for period');
        }

        foreach ($period as $date) {
            $seasonId = $this->getSeasonIdByDate($date);
            if ($seasonId === null) {
                throw new \Exception("Not found season for date: {$date}");
            }
            $this->roomPriceRepository->updateRoomPrices(
                $roomId,
                $seasonId,
                $rateId,
                $guestsNumber,
                1,//вроде резидент/нерезидент
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
