<?php

declare(strict_types=1);

namespace Module\Booking\Application\Dto;

use Sdk\Module\Foundation\Support\Dto\Dto;

class CountryDto extends Dto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {}
}
