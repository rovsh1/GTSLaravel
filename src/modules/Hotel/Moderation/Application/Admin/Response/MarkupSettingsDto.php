<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Admin\Response;

use Module\Hotel\Moderation\Application\Admin\ResponseDto\MarkupSettings\CancelPeriodDto;
use Module\Hotel\Moderation\Application\Admin\ResponseDto\MarkupSettings\ConditionDto;
use Module\Hotel\Moderation\Domain\Hotel\Entity\MarkupSettings;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\Contracts\Domain\ValueObjectInterface;
use Module\Shared\Support\Dto\AbstractDomainBasedDto;

class MarkupSettingsDto extends AbstractDomainBasedDto
{
    public function __construct(
        public readonly int $vat,
        public readonly int $touristTax,
        public readonly array $earlyCheckIn,
        public readonly array $lateCheckOut,
        public readonly array $cancelPeriods,
    ) {}

    public static function fromDomain(EntityInterface|ValueObjectInterface|MarkupSettings $entity): static
    {
        return new static(
            $entity->vat()->value(),
            $entity->touristTax()->value(),
            ConditionDto::collectionFromDomain($entity->earlyCheckIn()->all()),
            ConditionDto::collectionFromDomain($entity->lateCheckOut()->all()),
            CancelPeriodDto::collectionFromDomain($entity->cancelPeriods()->all()),
        );
    }
}
