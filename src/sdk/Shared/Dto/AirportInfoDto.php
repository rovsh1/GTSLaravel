<?php

declare(strict_types=1);

namespace Sdk\Shared\Dto;

final class AirportInfoDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $cityId,
    ) {
    }
}
