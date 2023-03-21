<?php

namespace Module\Hotel\Application\Service;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Custom\Framework\Contracts\Bus\QueryBusInterface;
use Module\Hotel\Application\Dto\Info\RoomDto;
use Module\Hotel\Application\Query\GetRoomById;
use Module\Hotel\Domain\Entity\Season;
use Module\Hotel\Domain\Exception\Room\PriceRateNotFound;
use Module\Hotel\Domain\Exception\Room\RoomNotFound;
use Module\Hotel\Domain\Exception\Room\UnsupportedRoomGuestsNumber;
use Module\Hotel\Domain\Repository\PriceRateRepositoryInterface;
use Module\Hotel\Domain\Repository\RoomPriceRepositoryInterface;
use Module\Hotel\Domain\Repository\SeasonRepositoryInterface;

class RoomPriceUpdater
{
    public function __construct(
        private readonly RoomPriceRepositoryInterface $roomPriceRepository,
        private readonly SeasonRepositoryInterface    $seasonRepository,
        private readonly PriceRateRepositoryInterface $priceRateRepository,
        private readonly QueryBusInterface            $queryBus,
        private array                                 $hotelSeasons = [],
    ) {}

    public function updateRoomPriceByPeriod(int $roomId, CarbonPeriod $period, int $rateId, int $guestsNumber, bool $isResident, float $price, string $currencyCode)
    {
        if ($currencyCode !== env('DEFAULT_CURRENCY_CODE')) {
            //Конвертация валюты не требуется, уточнял у Анвара. По договору, отель должен заносить цены в сумах в тревелайн.
            \Log::warning('Currency not supported', ['currencyCode' => $currencyCode, 'room_id' => $roomId, 'rate_id' => $rateId, 'guests_number' => $guestsNumber]);
            return;
        }

        $this->hotelSeasons = $this->seasonRepository->getActiveSeasonsByRoomIdIncludesPeriod($roomId, $period);
        if (count($this->hotelSeasons) === 0) {
            \Log::warning('Not found hotel season for period', ['period' => $period]);
            return;
        }

        /** @var RoomDto $room */
        $room = $this->queryBus->execute(new GetRoomById($roomId));
        if ($room === null) {
            throw new RoomNotFound("Room with id {$roomId} not found");
        }
        if ($guestsNumber <= 0 || $guestsNumber > $room->guestsNumber) {
            throw new UnsupportedRoomGuestsNumber("Unsupported guests number {$guestsNumber} for room with id {$roomId}");
        }
        if (!$this->priceRateRepository->existsByRoomAndRate($roomId, $rateId)) {
            throw new PriceRateNotFound("Price rate with id {$rateId}, not found for room with id {$roomId}");
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
