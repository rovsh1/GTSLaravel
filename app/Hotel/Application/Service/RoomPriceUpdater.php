<?php

namespace GTS\Hotel\Application\Service;

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

        //@todo получение сезона по комнате + join hotel_rooms (Через query)
        $this->hotelSeasons = $this->seasonRepository->getActiveSeasonsIncludesPeriod(333, $period);
        if (count($this->hotelSeasons) === 0) {
            //@todo уточнить у Анвара
            \Log::warning('Not found hotel season for period');
            return;
        }

        foreach ($period as $date) {
            $seasonId = $this->getSeasonIdByDate($date);
            if ($seasonId === null) {
                //@todo уточнить у Анвара
                \Log::warning("Not found season for date: {$date}");
                continue;
            }
            $this->roomPriceRepository->updateRoomPrices(
                $roomId,
                $seasonId,
                $rateId,
                $guestsNumber,
                1,//@todo вроде резидент/нерезидент - узнать у Анвара
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
