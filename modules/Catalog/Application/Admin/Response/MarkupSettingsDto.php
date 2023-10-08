<?php

declare(strict_types=1);

namespace Module\Catalog\Application\Admin\Response;

use Module\Catalog\Application\Admin\ResponseDto\MarkupSettings\CancelPeriodDto;
use Module\Catalog\Application\Admin\ResponseDto\MarkupSettings\ClientMarkupsDto;
use Module\Catalog\Application\Admin\ResponseDto\MarkupSettings\ConditionDto;
use Module\Catalog\Domain\Hotel\Entity\MarkupSettings;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class MarkupSettingsDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $vat,
        public readonly int $touristTax,
        public readonly ClientMarkupsDto $clientMarkups,
        public readonly array $earlyCheckIn,
        public readonly array $lateCheckOut,
        public readonly array $cancelPeriods,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|MarkupSettings $entity): static
    {
        return new static(
            $entity->vat()->value(),
            $entity->touristTax()->value(),
            ClientMarkupsDto::fromDomain($entity->clientMarkups()),
            ConditionDto::collectionFromDomain($entity->earlyCheckIn()->all()),
            ConditionDto::collectionFromDomain($entity->lateCheckOut()->all()),
            CancelPeriodDto::collectionFromDomain($entity->cancelPeriods()->all()),
        );
    }
}