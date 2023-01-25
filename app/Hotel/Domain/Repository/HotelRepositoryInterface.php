<?php

namespace GTS\Hotel\Domain\Repository;

use GTS\Hotel\Domain\Model\Hotel;

interface HotelRepositoryInterface
{
    public function findById(int $id): Hotel;
}
