<?php

namespace Module\Hotel\Application\Query;

use Module\Hotel\Application\Response\RoomDto;
use Module\Hotel\Domain\Repository\RoomRepositoryInterface;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class FindRoomHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly RoomRepositoryInterface $repository
    ) {}

    public function handle(QueryInterface|FindRoom $query): ?RoomDto
    {
        $room = $this->repository->find($query->id);
        if ($room === null) {
            return null;
        }
        return RoomDto::fromDomain($room);
    }
}
