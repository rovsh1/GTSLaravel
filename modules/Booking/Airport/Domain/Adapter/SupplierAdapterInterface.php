<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Adapter;

use Module\Supplier\Application\Supplier\Response\SupplierDto;

interface SupplierAdapterInterface
{
    public function find(int $id): SupplierDto;
}
