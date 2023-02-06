<?php

namespace GTS\Services\Integration\Traveline\Application\Dto;

use GTS\Shared\Application\Dto\Attributes\MapOutputName;
use GTS\Shared\Application\Dto\Dto;

class RatePlanDto extends Dto
{
    public function __construct(
        #[MapOutputName('ratePlanId')]
        public readonly int    $id,
        #[MapOutputName('ratePlanName')]
        public readonly string $name,
    ) {}
}
