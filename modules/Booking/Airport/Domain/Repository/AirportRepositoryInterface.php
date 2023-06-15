<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Repository;

use Module\Booking\Airport\Domain\Entity\Airport;

interface AirportRepositoryInterface
{
    public function get(int $id): ?Airport;
}
