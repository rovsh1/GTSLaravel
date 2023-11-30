<?php

namespace Module\Shared\Support\Dto;

abstract class AbstractDomainBasedDto
{
    abstract public static function fromDomain(mixed $entity): static;

    public static function collectionFromDomain(array $collection): array
    {
        return array_map(fn($entity) => static::fromDomain($entity), $collection);
    }
}
