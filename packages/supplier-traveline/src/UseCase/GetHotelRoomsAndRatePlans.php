<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\UseCase;

use Pkg\Supplier\Traveline\Dto\HotelDto;
use Pkg\Supplier\Traveline\Service\HotelService;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetHotelRoomsAndRatePlans implements UseCaseInterface
{
    public function __construct(
        private readonly HotelService $hotelService,
    ) {}

    public function execute(int $hotelId): HotelDto
    {
        return $this->hotelService->getHotelRoomsAndRatePlans($hotelId);
    }
}
