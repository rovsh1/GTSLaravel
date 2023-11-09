<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\UseCase;

use Module\Hotel\Moderation\Application\Dto\RoomDto;
use Module\Hotel\Moderation\Application\Factory\RoomDtoFactory;
use Module\Hotel\Moderation\Domain\Hotel\Repository\RoomRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetRooms implements UseCaseInterface
{
    public function __construct(
        private readonly RoomRepositoryInterface $repository,
    ) {
    }

    /**
     * @param int $hotelId
     * @return RoomDto[]
     */
    public function execute(int $hotelId): array
    {
        $rooms = $this->repository->getRoomsWithPriceRatesByHotelId($hotelId);

        return (new RoomDtoFactory())->collection($rooms);
    }
}
