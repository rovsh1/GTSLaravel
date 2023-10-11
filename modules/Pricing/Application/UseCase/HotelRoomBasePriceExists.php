<?php

declare(strict_types=1);

namespace Module\Pricing\Application\UseCase;

use DateTimeInterface;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class HotelRoomBasePriceExists implements UseCaseInterface
{
    public function __construct(
        private readonly \Module\Pricing\Infrastructure\Service\HotelRoomBaseDayValueFinder $domainUseCase
    ) {
    }

    public function execute(
        int $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        DateTimeInterface $date,
        CurrencyEnum $outCurrency = null
    ): bool {
        return (bool)$this->domainUseCase->execute($roomId, $rateId, $isResident, $guestsCount, $date, $outCurrency);
    }
}
