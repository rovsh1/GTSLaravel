<?php

declare(strict_types=1);

namespace Module\Booking\Application\Shared\Response;

use Sdk\Module\Foundation\Support\Dto\Dto;

class CountryDto extends Dto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {}
}
