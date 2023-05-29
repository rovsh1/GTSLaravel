<?php

namespace Module\HotelOld\Application\Query;

use Module\HotelOld\Application\Dto\Info\RoomDto;
use Module\HotelOld\Domain\Repository\RoomRepositoryInterface;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class GetRoomByIdHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly RoomRepositoryInterface $repository
    ) {}

    public function handle(QueryInterface|GetRoomById $query): ?RoomDto
    {
        $room = $this->repository->find($query->id);
        if ($room === null) {
            return null;
        }
        return RoomDto::from($room);
    }
}
