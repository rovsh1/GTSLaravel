<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Application\Response;

use Module\Shared\Support\Dto\AbstractDomainBasedDto;
use Module\Supplier\Moderation\Domain\Supplier\Entity\Contract;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\ServiceId;

class ServiceContractDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $supplierId,
        public readonly array $serviceIds,
//        public readonly ContractServiceTypeEnum $serviceType,
        public readonly string $dateStart,
        public readonly string $dateEnd,
    ) {}

    public static function fromDomain(mixed $entity): static
    {
        assert($entity instanceof Contract);

        return new static(
            $entity->id()->value(),
            $entity->supplierId()->value(),
            $entity->serviceIds()->map(fn(ServiceId $serviceId) => $serviceId->value()),
//            $entity->serviceType(),
            (string)$entity->dateStart(),
            (string)$entity->dateEnd(),
        );
    }
}
