<?php

namespace Module\Hotel\Domain\Repository;

use Module\Hotel\Domain\Entity\Hotel;

interface HotelRepositoryInterface
{
    public function find(int $id): ?Hotel;

    public function store(Hotel $hotel): bool;
}
