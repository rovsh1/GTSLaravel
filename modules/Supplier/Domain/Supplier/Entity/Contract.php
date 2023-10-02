<?php

declare(strict_types=1);

namespace Module\Supplier\Domain\Supplier\Entity;

use Module\Booking\Airport\Domain\Booking\ValueObject\ContractId;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Date;
use Module\Shared\Enum\Supplier\ContractServiceTypeEnum;
use Module\Supplier\Domain\Supplier\ValueObject\ServiceId;
use Module\Supplier\Domain\Supplier\ValueObject\SupplierId;

class Contract implements EntityInterface
{
    public function __construct(
        private readonly ContractId $id,
        private readonly SupplierId $supplierId,
        private readonly ServiceId $serviceId,
        private readonly ContractServiceTypeEnum $serviceType,
        private readonly Date $dateStart,
        private readonly Date $dateEnd,
    ) {}

    public function id(): ContractId
    {
        return $this->id;
    }

    public function supplierId(): SupplierId
    {
        return $this->supplierId;
    }

    public function serviceId(): ServiceId
    {
        return $this->serviceId;
    }

    public function serviceType(): ContractServiceTypeEnum
    {
        return $this->serviceType;
    }

    public function dateStart(): Date
    {
        return $this->dateStart;
    }

    public function dateEnd(): Date
    {
        return $this->dateEnd;
    }
}
