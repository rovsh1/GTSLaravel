<?php

declare(strict_types=1);

namespace Pkg\Booking\Requesting\Domain\Adapter;

interface SupplierAdapterInterface
{
    public function getEmail(int $supplierId): ?string;
}
