<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\ResponseDto\MarkupSettings;

final class ConditionDto
{
    public function __construct(
        public readonly string $from,
        public readonly string $to,
        public readonly int $percent,
    ) {
    }
}
