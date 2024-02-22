<?php

declare(strict_types=1);

namespace Module\Hotel\Pricing\Application\Dto;

final class RoomCalculationParamsDto
{
    public function __construct(
        public readonly int $accommodationId,
        public readonly int $roomId,
        public readonly int $rateId,
        public readonly bool $isResident,
        public readonly int $guestsCount,
        public readonly ?float $manualDayPrice,
        public readonly ?int $earlyCheckinPercent,
        public readonly ?int $lateCheckoutPercent,
    ) {
    }
}
