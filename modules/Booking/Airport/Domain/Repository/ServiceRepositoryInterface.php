<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Repository;

use Carbon\CarbonPeriod;
use Module\Booking\Airport\Domain\Entity\Service;

interface ServiceRepositoryInterface
{
    public function getServiceBySeasonPeriod(int $id, CarbonPeriod $period): Service;
}
