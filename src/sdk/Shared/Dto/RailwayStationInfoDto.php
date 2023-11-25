<?php

declare(strict_types=1);

namespace Sdk\Shared\Dto;

final class RailwayStationInfoDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $cityId,
        public readonly string $name,
    ) {}
}
