<?php

namespace Module\Booking\Hotel\Domain\Repository;

use Module\Booking\Hotel\Domain\Entity\Details;

interface DetailsRepositoryInterface
{
    public function find(int $id): ?Details;

    public function update(Details $details): bool;
}
