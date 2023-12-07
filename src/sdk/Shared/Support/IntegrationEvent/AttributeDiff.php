<?php

namespace Sdk\Shared\Support\IntegrationEvent;

class AttributeDiff
{
    public function __construct(
        public readonly string $name,
        public readonly string $type,
        public readonly mixed $before,
        public readonly mixed $after,
    ) {}
}