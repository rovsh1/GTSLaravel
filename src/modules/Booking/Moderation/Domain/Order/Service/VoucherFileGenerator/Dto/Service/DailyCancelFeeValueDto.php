<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator\Dto\Service;

use Sdk\Shared\Enum\Pricing\ValueTypeEnum;

class DailyCancelFeeValueDto
{
    public function __construct(
        public readonly float $value,
        public readonly ValueTypeEnum $valueType,
        public readonly int $daysCount,
        public readonly string $markupType,
    ) {}
}
