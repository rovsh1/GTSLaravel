<?php

namespace GTS\Integration\Traveline\Application\Dto;

use Custom\Dto\Attributes\MapOutputName;
use Custom\Dto\Dto;

class RatePlanDto extends Dto
{
    public function __construct(
        #[MapOutputName('ratePlanId')]
        public readonly int    $id,
        #[MapOutputName('ratePlanName')]
        public readonly string $name,
    ) {}
}
