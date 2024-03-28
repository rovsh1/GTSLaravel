<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\UseCase;

use Pkg\Supplier\Traveline\Repository\HotelRepository;

class CheckHotelIntegrationEnabled
{
    public function __construct(
        private readonly HotelRepository $hotelRepository
    ) {}

    public function execute(int $hotelId): bool
    {
        return $this->hotelRepository->isHotelIntegrationEnabled($hotelId);
    }
}
