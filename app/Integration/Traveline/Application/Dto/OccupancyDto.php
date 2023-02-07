<?php

namespace GTS\Integration\Traveline\Application\Dto;

use GTS\Shared\Application\Dto\Attributes\MapOutputName;
use GTS\Shared\Application\Dto\Dto;

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
