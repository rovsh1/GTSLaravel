<?php

namespace Module\Hotel\Application\Query;

use Module\Hotel\Application\Dto\Room\MarkupSettingsDto;
use Module\Hotel\Domain\Repository\RoomMarkupSettingsRepositoryInterface;
use Sdk\Module\Contracts\Bus\QueryHandlerInterface;
use Sdk\Module\Contracts\Bus\QueryInterface;

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
