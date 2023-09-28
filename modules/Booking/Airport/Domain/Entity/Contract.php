<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Entity;

use Module\Booking\Airport\Domain\ValueObject\ContractId;
use Module\Shared\Domain\Entity\EntityInterface;
use Module\Shared\Domain\ValueObject\Date;

class Contract implements EntityInterface
{
    public function __construct(
        private readonly ContractId $id,
        private readonly Date $dateStart,
        private readonly Date $dateEnd,
    ) {}

    public function id(): ContractId
    {
        return $this->id;
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
