<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\UseCase;

use Module\Hotel\Moderation\Application\Dto\HotelDto;
use Module\Hotel\Moderation\Domain\Hotel\Repository\HotelRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class FindHotelById implements UseCaseInterface
{
    public function __construct(
        private readonly HotelRepositoryInterface $hotelRepository
    ) {}

    public function execute(int $id): ?HotelDto
    {
        $hotel = $this->hotelRepository->find($id);
        if ($hotel === null) {
            return null;
        }

        return HotelDto::fromDomain($hotel);
    }
}
