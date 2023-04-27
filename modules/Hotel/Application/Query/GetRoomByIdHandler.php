<?php

namespace Module\Hotel\Application\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Hotel\Application\Dto\RoomDto;
use Module\Hotel\Domain\Repository\RoomRepositoryInterface;

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
