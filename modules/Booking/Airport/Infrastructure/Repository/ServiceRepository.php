<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Infrastructure\Repository;

use Carbon\CarbonPeriod;
use Module\Booking\Airport\Domain\Entity\Service;
use Module\Booking\Airport\Domain\Factory\ServiceFactory;
use Module\Booking\Airport\Domain\Repository\ServiceRepositoryInterface;
use Module\Booking\Airport\Infrastructure\Models\Service as Model;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function __construct(
        private readonly ServiceFactory $factory,
    ) {}

    public function get(int $id): ?Service
    {
        $model = Model::find($id);
        if ($model === null) {
            return null;
        }

        return $this->factory->createFrom($model);
    }

    public function getServiceBySeasonPeriod(int $id, CarbonPeriod $period): Service
    {
        // TODO: Implement getServiceBySeasonPeriod() method.
    }
}
