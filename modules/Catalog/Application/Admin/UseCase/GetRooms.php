<?php

declare(strict_types=1);

namespace Module\Catalog\Application\Admin\UseCase;

use Module\Catalog\Application\Admin\Response\RoomDto;
use Module\Catalog\Domain\Hotel\Repository\RoomRepositoryInterface;
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
