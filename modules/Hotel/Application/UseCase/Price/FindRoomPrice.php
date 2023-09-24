<?php

declare(strict_types=1);

namespace Module\Hotel\Application\UseCase\Price;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Module\Hotel\Application\Query\Price\Date\Find as Query;
use Module\Hotel\Application\Query\Price\Season\Find as SeasonPriceQuery;
use Module\Hotel\Application\Response\PriceDto;
use Module\Hotel\Domain\Repository\SeasonRepositoryInterface;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class FindRoomPrice implements UseCaseInterface
{
    public function __construct(
        private readonly SeasonRepositoryInterface $seasonRepository,
        private readonly QueryBusInterface $queryBus
    ) {}

    public function execute(
        int $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        CarbonInterface $date
    ): ?PriceDto {
        $seasons = $this->seasonRepository->getActiveSeasonsByRoomIdIncludesPeriod(
            $roomId,
            new CarbonPeriod($date->startOfDay(), $date->endOfDay())
        );
        if (count($seasons) === 0 || count($seasons) > 1) {
            //@todo норм ексепшн
            throw new \Exception('Seasons error');
        }
        $season = current($seasons);

        $datePrice = $this->queryBus->execute(
            new Query($roomId, $season->id()->value(), $rateId, $isResident, $guestsCount, $date)
        );
        if ($datePrice !== null) {
            return $datePrice;
        }
        $seasonPrice = $this->queryBus->execute(
            new SeasonPriceQuery($roomId, $season->id()->value(), $rateId, $isResident, $guestsCount)
        );

        return $seasonPrice;
    }
}
