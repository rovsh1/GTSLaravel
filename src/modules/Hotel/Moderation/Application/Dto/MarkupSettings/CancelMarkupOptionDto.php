<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Dto\MarkupSettings;

final class CancelMarkupOptionDto
{
    public function __construct(
        public readonly int $percent,
        public readonly int $cancelPeriodType
    ) {
    }
}
