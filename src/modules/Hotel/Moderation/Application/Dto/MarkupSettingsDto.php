<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Dto;

use Module\Hotel\Moderation\Application\Dto\MarkupSettings\CancelPeriodDto;
use Module\Hotel\Moderation\Application\Dto\MarkupSettings\ConditionDto;

final class MarkupSettingsDto
{
    /**
     * @param int $vat
     * @param int $touristTax
     * @param ConditionDto[] $earlyCheckIn
     * @param ConditionDto[] $lateCheckOut
     * @param CancelPeriodDto[] $cancelPeriods
     */
    public function __construct(
        public readonly int $vat,
        public readonly int $touristTax,
        public readonly array $earlyCheckIn,
        public readonly array $lateCheckOut,
        public readonly array $cancelPeriods,
    ) {
    }
}
