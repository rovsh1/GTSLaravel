<?php

namespace Module\Shared\Support\Dto;

use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;

/**
 * @deprecated
 */
abstract class AbstractDomainBasedDto extends \Sdk\Module\Foundation\Support\Dto\Dto
{
    abstract public static function fromDomain(EntityInterface|ValueObjectInterface $entity): static;

    public static function collectionFromDomain(array $collection): array
    {
        return array_map(fn(EntityInterface|ValueObjectInterface $entity) => static::fromDomain($entity), $collection);
    }
}
