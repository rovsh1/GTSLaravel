<?php

namespace Supplier\Traveline\Domain\Repository;

interface HotelRepositoryInterface
{
    public function isHotelIntegrationEnabled(int $hotelId): bool;

    public function getIntegratedHotelIds(): array;
}
