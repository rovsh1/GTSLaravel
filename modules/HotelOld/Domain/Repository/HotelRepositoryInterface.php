<?php

namespace Module\HotelOld\Domain\Repository;

use Module\HotelOld\Domain\Entity\Hotel;

interface HotelRepositoryInterface
{
    public function find(int $id): ?Hotel;
}
