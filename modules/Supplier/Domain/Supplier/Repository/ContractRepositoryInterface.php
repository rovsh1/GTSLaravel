<?php

declare(strict_types=1);

namespace Module\Supplier\Domain\Supplier\Repository;

use Module\Supplier\Domain\Supplier\Entity\Contract;

interface ContractRepositoryInterface
{
    public function find(int $serviceId): ?Contract;
}
