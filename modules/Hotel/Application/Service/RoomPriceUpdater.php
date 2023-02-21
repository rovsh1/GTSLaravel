<?php

namespace Module\Hotel\Application\Service;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Custom\Framework\Contracts\Bus\QueryBusInterface;
use Module\Hotel\Application\Query\GetActiveSeasonsByRoomIdIncludesPeriod;
use Module\Hotel\Domain\Entity\Season;
use Module\Hotel\Domain\Repository\RoomPriceRepositoryInterface;

class RoomPriceUpdater
{
    public function __construct(
        private RoomPriceRepositoryInterface $roomPriceRepository,
        private QueryBusInterface            $queryBus,
        private array                        $hotelSeasons = [],
    ) {}

    public function updateRoomPriceByPeriod(int $roomId, CarbonPeriod $period, int $rateId, int $guestsNumber, bool $isResident, float $price, string $currencyCode)
    {
        //@todo нормальная проверка на валюту и норм. ексепшн
        if ($currencyCode !== 'UZS') {
            throw new \Exception('Currency not supported');
        }

        $this->hotelSeasons = $this->queryBus->execute(new GetActiveSeasonsByRoomIdIncludesPeriod($roomId, $period));
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
