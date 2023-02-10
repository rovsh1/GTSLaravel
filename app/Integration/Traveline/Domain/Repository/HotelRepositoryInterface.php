<?php

namespace GTS\Integration\Traveline\Domain\Repository;

interface HotelRepositoryInterface
{
    public function isHotelIntegrationEnabled(int $hotelId): bool;
}
