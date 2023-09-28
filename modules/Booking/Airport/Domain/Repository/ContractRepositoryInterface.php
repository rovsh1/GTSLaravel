<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Repository;

use Module\Booking\Airport\Domain\Entity\Contract;

interface ContractRepositoryInterface
{
    public function find(int $serviceId): ?Contract;
}
