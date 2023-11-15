<?php

declare(strict_types=1);

namespace Module\Supplier\Moderation\Domain\Supplier\Repository;

use Module\Supplier\Moderation\Domain\Supplier\Supplier;

interface SupplierRepositoryInterface
{
    public function find(int $id): ?Supplier;
}
