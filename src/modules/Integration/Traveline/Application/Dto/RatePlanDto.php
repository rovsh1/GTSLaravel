<?php

namespace Module\Integration\Traveline\Application\Dto;

use Sdk\Module\Foundation\Support\Dto\Attributes\MapOutputName;
use Sdk\Module\Foundation\Support\Dto\Dto;

class RatePlanDto extends Dto
{
    public function __construct(
        #[MapOutputName('ratePlanId')]
        public readonly int    $id,
        #[MapOutputName('ratePlanName')]
        public readonly string $name,
    ) {}
}
