<?php

declare(strict_types=1);

namespace Module\Supplier\Application\Supplier\Response;

use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;
use Module\Supplier\Domain\Supplier\Supplier;

class SupplierDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $inn,
        public readonly ?string $directorFullName,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface $entity): static
    {
        assert($entity instanceof Supplier);

        return new static(
            $entity->id()->value(),
            $entity->name(),
            $entity->requisites()?->inn(),
            $entity->requisites()?->directorFullName(),
        );
    }
}
