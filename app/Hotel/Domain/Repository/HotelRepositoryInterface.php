<?php

namespace GTS\Hotel\Domain\Repository;

use GTS\Hotel\Domain\Entity\Hotel;

interface HotelRepositoryInterface
{
    public function findById(int $id): Hotel;
}
