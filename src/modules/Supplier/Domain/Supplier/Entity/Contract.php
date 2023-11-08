<?php

declare(strict_types=1);

namespace Module\Supplier\Domain\Supplier\Entity;

use Module\Booking\Shared\Domain\Shared\ValueObject\ContractId;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\ValueObject\Date;
use Module\Supplier\Domain\Supplier\ValueObject\ServiceIdCollection;
use Module\Supplier\Domain\Supplier\ValueObject\SupplierId;

class Contract implements EntityInterface
{
    public function __construct(
        private readonly ContractId $id,
        private readonly SupplierId $supplierId,
        private readonly ServiceIdCollection $serviceIds,
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

    public function serviceIds(): ServiceIdCollection
    {
        return $this->serviceIds;
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
