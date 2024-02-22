<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Domain\Supplier\Repository;

use Module\Supplier\Moderation\Domain\Supplier\Entity\Contract;

interface ContractRepositoryInterface
{
    public function find(int $serviceId): ?Contract;
}
