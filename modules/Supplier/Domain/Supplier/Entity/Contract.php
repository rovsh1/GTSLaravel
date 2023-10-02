<?php

declare(strict_types=1);

namespace Module\Supplier\Domain\Supplier\Entity;

use Module\Booking\Airport\Domain\ValueObject\ContractId;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Date;
use Module\Shared\Enum\Supplier\ContractServiceTypeEnum;
use Module\Supplier\Domain\Supplier\ValueObject\ServiceId;

class Contract implements EntityInterface
{
    public function __construct(
        private readonly ContractId $id,
        private readonly ServiceId $serviceId,
        private readonly ContractServiceTypeEnum $serviceType,
        private readonly Date $dateStart,
        private readonly Date $dateEnd,
    ) {}

    public function id(): ContractId
    {
        return $this->id;
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
