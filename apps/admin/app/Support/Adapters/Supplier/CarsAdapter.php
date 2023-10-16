<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Supplier;

use Module\Supplier\Application\UseCase\GetCars;

class CarsAdapter
{
    public function getCars(int $supplierId): array
    {
        return app(GetCars::class)->execute($supplierId);
    }
}
