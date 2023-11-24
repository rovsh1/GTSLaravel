<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\Service\Dto\Booking;

use Module\Shared\Enum\Pricing\ValueTypeEnum;

class DailyMarkupDto
{
    public function __construct(
        public readonly int $value,
        public readonly ValueTypeEnum $valueType,
        public readonly int $daysCount,
        public readonly string $markupType,
    ) {}
}
