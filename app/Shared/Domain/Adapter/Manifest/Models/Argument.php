<?php

namespace GTS\Shared\Domain\Adapter\Manifest\Models;

use Spatie\LaravelData\Data;

class Argument extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $type,
        public readonly bool   $isNullable,
        public readonly bool   $isDefaultValueAvailable,
        public readonly mixed  $defaultValue = null
    ) {}

    public function validationRules(): array
    {
        $rule = [$this->type];
        if ($this->isNullable) {
            $rule = 'nullable';
        }
        return [$this->name => $rule];
    }
}
