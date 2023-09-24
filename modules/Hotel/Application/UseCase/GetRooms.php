<?php

declare(strict_types=1);

namespace Module\Hotel\Application\UseCase;

use Module\Hotel\Application\Response\RoomDto;
use Module\Hotel\Domain\Repository\RoomRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetRooms implements UseCaseInterface
{
    public function __construct(
        private readonly RoomRepositoryInterface $repository,
    ) {}

    public function execute(int $hotelId): array
    {
        $rooms = $this->repository->getRoomsWithPriceRatesByHotelId($hotelId);

        return RoomDto::collectionFromDomain($rooms);
    }
}
