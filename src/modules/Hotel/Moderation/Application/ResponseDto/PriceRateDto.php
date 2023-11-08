<?php

namespace Module\Hotel\Moderation\Application\ResponseDto;

final class PriceRateDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $description
    ) {
    }
}
