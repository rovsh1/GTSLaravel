<?php

namespace Module\Catalog\Domain\Hotel\Repository;

use Module\Catalog\Domain\Hotel\Entity\Hotel;

interface HotelRepositoryInterface
{
    public function find(int $id): ?Hotel;

    public function store(Hotel $hotel): bool;
}
