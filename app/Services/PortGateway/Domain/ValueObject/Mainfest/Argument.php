<?php

namespace GTS\Services\PortGateway\Domain\ValueObject\Mainfest;

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

    public function isScalarType(): bool
    {
        return in_array($this->type, $this->scalarTypes());
    }

    private function scalarTypes(): array
    {
        return ['string', 'int', 'float', 'mixed', 'array'];
    }
}
