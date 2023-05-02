<?php

namespace Module\Shared\Application\Dto;

use Custom\Framework\Foundation\Support\Dto\Dto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

abstract class AbstractDomainBasedDto extends Dto
{
    abstract public static function fromDomain(EntityInterface|ValueObjectInterface $entity): self;

    public static function collectionFromDomain(array $collection): array
    {
        return array_map(fn(EntityInterface|ValueObjectInterface $entity) => static::fromDomain($entity), $collection);
    }
}
