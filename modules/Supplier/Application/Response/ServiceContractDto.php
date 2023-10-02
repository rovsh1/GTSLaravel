<?php

declare(strict_types=1);

namespace Module\Supplier\Application\Response;

use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;
use Module\Shared\Enum\Supplier\ContractServiceTypeEnum;
use Module\Supplier\Domain\Supplier\Entity\Contract;

class ServiceContractDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $supplierId,
        public readonly int $serviceId,
        public readonly ContractServiceTypeEnum $serviceType,
        public readonly string $dateStart,
        public readonly string $dateEnd,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface $entity): static
    {
        assert($entity instanceof Contract);

        return new static(
            $entity->id()->value(),
            $entity->supplierId()->value(),
            $entity->serviceId()->value(),
            $entity->serviceType(),
            (string)$entity->dateStart(),
            (string)$entity->dateEnd(),
        );
    }
}
