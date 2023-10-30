<?php

declare(strict_types=1);

namespace Module\Administrator\Domain\Repository;

use Module\Administrator\Domain\Entity\Administrator;

interface AdministratorRepositoryInterface
{
    public function find(int $id): ?Administrator;

    public function findByBookingId(int $bookingId): ?Administrator;
}
