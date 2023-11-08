<?php

namespace Module\Hotel\Moderation\Domain\Hotel\Repository;

use Module\Hotel\Moderation\Domain\Hotel\Hotel;

interface HotelRepositoryInterface
{
    public function find(int $id): ?Hotel;

    public function store(Hotel $hotel): bool;
}
