<?php

namespace Pkg\Supplier\Traveline\Dto;

use Custom\Framework\Foundation\Support\Dto\Attributes\MapOutputName;
use Custom\Framework\Foundation\Support\Dto\Dto;

class RatePlanDto extends Dto
{
    public function __construct(
        #[MapOutputName('ratePlanId')]
        public readonly int    $id,
        #[MapOutputName('ratePlanName')]
        public readonly string $name,
    ) {}
}
