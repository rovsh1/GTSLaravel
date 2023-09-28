<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Infrastructure\Adapter;

use Module\Booking\Airport\Domain\Adapter\SupplierAdapterInterface;
use Module\Supplier\Application\Supplier\Response\SupplierDto;
use Module\Supplier\Application\Supplier\UseCase\Find;

class SupplierAdapter implements SupplierAdapterInterface
{
    public function find(int $id): SupplierDto
    {
        return app(Find::class)->execute($id);
    }
}
