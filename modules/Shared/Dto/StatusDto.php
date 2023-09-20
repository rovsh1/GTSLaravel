<?php

declare(strict_types=1);

namespace Module\Shared\Dto;

class StatusDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {
    }

    public static function createFromEnum($enum): StatusDto
    {
        return new StatusDto(
            $enum->value,
            $enum->name
        );
    }
}
