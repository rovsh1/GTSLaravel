<?php

declare(strict_types=1);

namespace Module\Client\Application\Dto;

use Module\Client\Domain\Entity\Legal;
use Module\Shared\Application\Dto\AbstractDomainBasedDto;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

final class LegalDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $type,
        public readonly ?int $industryId,
        public readonly ?string $address,
        public readonly ?string $bik,
        public readonly ?string $cityName,
        public readonly ?string $inn,
        public readonly ?string $okpo,
        public readonly ?string $correspondentAccount,
        public readonly ?string $kpp,
        public readonly ?string $bankName,
        public readonly ?string $currentAccount,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|Legal $entity): static
    {
        return new static(
            $entity->id()->value(),
            $entity->name(),
            $entity->type()->value,
            $entity->industryId()?->value(),
            $entity->address(),
            $entity->requisites()?->bik,
            $entity->requisites()?->cityName,
            $entity->requisites()?->inn,
            $entity->requisites()?->okpo,
            $entity->requisites()?->correspondentAccount,
            $entity->requisites()?->kpp,
            $entity->requisites()?->bankName,
            $entity->requisites()?->currentAccount,
        );
    }
}
