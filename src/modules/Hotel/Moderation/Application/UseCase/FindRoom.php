<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\UseCase;

use Module\Hotel\Moderation\Application\Dto\RoomDto;
use Module\Hotel\Moderation\Application\Factory\RoomDtoFactory;
use Module\Hotel\Moderation\Domain\Hotel\Repository\RoomRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class FindRoom implements UseCaseInterface
{
    public function __construct(
        private readonly RoomRepositoryInterface $roomRepository
    ) {
    }

    public function execute(int $id): ?RoomDto
    {
        $room = $this->roomRepository->find($id);
        if ($room === null) {
            return null;
        }

        return (new RoomDtoFactory())->fromRoom($room);
    }
}
