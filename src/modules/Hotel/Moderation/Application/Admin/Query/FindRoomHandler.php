<?php

namespace Module\Hotel\Moderation\Application\Admin\Query;

use Module\Hotel\Moderation\Application\Admin\Response\RoomDto;
use Module\Hotel\Moderation\Domain\Hotel\Repository\RoomRepositoryInterface;
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
