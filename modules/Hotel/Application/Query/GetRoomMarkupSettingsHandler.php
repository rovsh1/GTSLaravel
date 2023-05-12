<?php

namespace Module\Hotel\Application\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Hotel\Application\Dto\Room\MarkupSettingsDto;
use Module\Hotel\Domain\Repository\RoomMarkupSettingsRepositoryInterface;

class GetRoomMarkupSettingsHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly RoomMarkupSettingsRepositoryInterface $repository
    ) {}

    public function handle(QueryInterface|GetRoomMarkupSettings $query): MarkupSettingsDto
    {
        $markup = $this->repository->get($query->roomId);

        return MarkupSettingsDto::from($markup);
    }
}
