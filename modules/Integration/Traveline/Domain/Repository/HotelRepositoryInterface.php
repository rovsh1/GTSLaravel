<?php

namespace Module\Integration\Traveline\Domain\Repository;

interface HotelRepositoryInterface
{
    public function isHotelIntegrationEnabled(int $hotelId): bool;
}
