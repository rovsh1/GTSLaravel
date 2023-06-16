<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Infrastructure\Repository;

use Module\Booking\Airport\Domain\Entity\Airport;
use Module\Booking\Airport\Domain\Factory\AirportFactory;
use Module\Booking\Airport\Domain\Repository\AirportRepositoryInterface;
use Module\Booking\Airport\Infrastructure\Models\Airport as Model;

class AirportRepository implements AirportRepositoryInterface
{
    public function __construct(
        private readonly AirportFactory $factory
    ) {}

    public function get(int $id): ?Airport
    {
        $model = Model::find($id);
        if ($model === null) {
            return null;
        }

        return $this->factory->createFrom($model);
    }
}
