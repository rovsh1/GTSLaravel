<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\ResponseDto\MarkupSettings;

final class DailyMarkupDto
{
    public function __construct(
        public readonly int $percent,
        public readonly int $cancelPeriodType,
        public readonly int $daysCount
    ) {
    }
}
