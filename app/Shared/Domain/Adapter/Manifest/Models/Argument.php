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
        $rule = [];
        if (in_array($this->type, $this->scalarTypes())) {
            $rule[] = $this->type;
        }
        if ($this->isNullable) {
            $rule = 'nullable';
        }
        return [$this->name => $rule];
    }

    private function scalarTypes(): array
    {
        return ['string', 'int', 'float', 'mixed', 'array'];
    }
}
