<?php

declare(strict_types=1);

namespace Module\Supplier\Domain\Supplier\Repository;

use Module\Supplier\Domain\Supplier\Supplier;

interface SupplierRepositoryInterface
{
    public function find(int $id): ?Supplier;
}
