<?php

namespace Module\Hotel\Application\Service;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Module\Hotel\Application\Service\FindDatePrice;
use Module\Hotel\Application\Service\RoomPrices;
use Module\Hotel\Application\Service\ValueObject;
use Module\Hotel\Domain\Repository\SeasonRepositoryInterface;
use Module\Hotel\Domain\Repository\RoomPriceRepositoryInterface;

class PricePeriodUpdater
{
    public function __construct(
        private RoomPriceRepositoryInterface $roomPriceRepository,
        private SeasonRepositoryInterface $seasonRepository,
        private array $hotelSeasons = [],
    ) {}

    public function updateRoomPriceByDate(int $roomId, CarbonPeriod $period, int $rateId, int $guestsNumber, float $price, string $currencyCode)
    {
        //@todo нормальная проверка на валюту и норм. ексепшн
        if ($currencyCode !== 'USZ') {
            throw new \Exception('Currency not supported');
        }

        $prices = new RoomPrices($roomId, $period);

        $this->roomPriceRepository->updateRoomPrices();

        //$datesGenerator = $this->validatePeriod($period); // Generator
        foreach ($period as $date) {
            $seasonId = $this->getSeasonIdByDate($date);

            $price = $this->queryBus->execute(new FindDatePrice(
                $date,
                $seasonId,
                $roomId,
                $rateId,
                $guestsNumber,
                $residentType,
                $aggregatorId,
            ));
            if ($price) {
                $price = new ValueObject\Price($price, $currencyCode);
                $this->priceRepository->store($price);
            } else {
                $price = $this->priceRepository->create(
                    $date,
                    $seasonId,
                    $roomId,
                    $rateId,
                    $guestsNumber,
                    $residentType,
                    $aggregatorId,
                    $price,
                    $currencyCode
                );
            }
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

    private function getSeasonIdByDate(int $roomId, CarbonInterface $date): ?int
    {
        foreach ($this->hotelSeasons as $season) {
            if ($season->hasDate($date)) {
                return $season->id();
            }
        }

        $season = $this->seasonRepository->findActiveRoomSeasonByDate($roomId, $date);
        if (!$season) {
            return null;
        }

        $this->hotelSeasons[] = $season;

        return $season->id();
    }
}


