<?php

declare(strict_types=1);

namespace Module\Client\Moderation\Application\Admin\Dto;

use Module\Client\Moderation\Domain\Entity\Legal;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

final class LegalDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
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

    public static function fromDomain(mixed $entity): static
    {
        assert($entity instanceof Legal);

        return new static(
            $entity->id()->value(),
            $entity->name(),
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
