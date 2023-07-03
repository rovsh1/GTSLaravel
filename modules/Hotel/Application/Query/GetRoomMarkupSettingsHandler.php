<?php

namespace Module\Hotel\Application\Query;

use Module\Hotel\Application\Response\RoomMarkupSettingsDto;
use Module\Hotel\Domain\Repository\RoomMarkupSettingsRepositoryInterface;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

class GetRoomMarkupSettingsHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly RoomMarkupSettingsRepositoryInterface $repository
    ) {}

    public function handle(QueryInterface|GetRoomMarkupSettings $query): RoomMarkupSettingsDto
    {
        $markup = $this->repository->get($query->roomId);

        return RoomMarkupSettingsDto::from($markup);
    }
}
