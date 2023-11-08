<?php

namespace Module\Hotel\Moderation\Application\Admin\Query;

use Module\Hotel\Moderation\Application\Admin\Response\RoomMarkupsDto;
use Module\Hotel\Moderation\Domain\Hotel\Repository\RoomMarkupSettingsRepositoryInterface;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class GetRoomMarkupsHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly RoomMarkupSettingsRepositoryInterface $repository
    ) {}

    public function handle(QueryInterface $query): ?RoomMarkupsDto
    {
        assert($query instanceof GetRoomMarkups);

        $markup = $this->repository->get($query->roomId);
        if ($markup === null) {
            return null;
        }

        return RoomMarkupsDto::from($markup);
    }
}
