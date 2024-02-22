<?php

declare(strict_types=1);

namespace Sdk\Shared\Dto;

final class CountryDto
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $name,
    ) {
    }
}
