<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\Response;

use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;
use Module\Supplier\Moderation\Domain\Supplier\Supplier;

class SupplierDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $inn,
        public readonly ?string $directorFullName,
        public readonly CurrencyEnum $currency,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface $entity): static
    {
        assert($entity instanceof Supplier);

        return new static(
            $entity->id()->value(),
            $entity->name(),
            $entity->requisites()?->inn(),
            $entity->requisites()?->directorFullName(),
            $entity->currency()
        );
    }
}
