<?php

namespace GTS\Services\Integration\Traveline\Application\Dto;

use GTS\Shared\Application\Dto\Dto;

class RatePlanDto extends Dto
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
    ) {}
}
