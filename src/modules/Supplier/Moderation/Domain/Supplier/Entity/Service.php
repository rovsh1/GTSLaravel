<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Domain\Supplier\Entity;

use Module\Shared\Enum\ServiceTypeEnum;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\ServiceId;
use Module\Supplier\Moderation\Domain\Supplier\ValueObject\SupplierId;

class Service
{
    public function __construct(
        private readonly ServiceId $serviceId,
        private readonly SupplierId $supplierId,
        private readonly string $title,
        private readonly ServiceTypeEnum $type
    ) {}

    public function id(): ServiceId
    {
        return $this->serviceId;
    }

    public function supplierId(): SupplierId
    {
        return $this->supplierId;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function type(): ServiceTypeEnum
    {
        return $this->type;
    }
}
