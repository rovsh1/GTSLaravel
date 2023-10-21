<?php

declare(strict_types=1);

namespace Module\Supplier\Domain\Supplier\Entity;

use Module\Booking\Deprecated\AirportBooking\ValueObject\ContractId;
use Module\Shared\Contracts\Domain\EntityInterface;
use Module\Shared\ValueObject\Date;
use Module\Supplier\Domain\Supplier\ValueObject\ServiceId;
use Module\Supplier\Domain\Supplier\ValueObject\SupplierId;

class Contract implements EntityInterface
{
    public function __construct(
        private readonly ContractId $id,
        private readonly SupplierId $supplierId,
        private readonly ServiceId $serviceId,
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

    public function dateStart(): Date
    {
        return $this->dateStart;
    }

    public function dateEnd(): Date
    {
        return $this->dateEnd;
    }
}
