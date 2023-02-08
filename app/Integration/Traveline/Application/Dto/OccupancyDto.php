<?php

namespace GTS\Integration\Traveline\Application\Dto;

use Custom\Framework\Foundation\Support\Dto\Attributes\MapOutputName;
use Custom\Framework\Foundation\Support\Dto\Dto;

class OccupancyDto extends Dto
{
    private const DEFAULT_BED_TYPE = 'adultBed';

    public function __construct(
        #[MapOutputName('code')]
        public readonly int $id,
        public readonly int $personQuantity,
        public readonly string $bedType = self::DEFAULT_BED_TYPE,
    ) {}
}
