<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\ResponseDto\MarkupSettings;

use Carbon\CarbonInterface;

final class CancelPeriodDto
{
    /**
     * @param CarbonInterface $from
     * @param CarbonInterface $to
     * @param CancelMarkupOptionDto $noCheckInMarkup
     * @param DailyMarkupDto[] $dailyMarkups
     */
    public function __construct(
        public readonly CarbonInterface $from,
        public readonly CarbonInterface $to,
        public readonly CancelMarkupOptionDto $noCheckInMarkup,
        public readonly array $dailyMarkups
    ) {
    }
}
