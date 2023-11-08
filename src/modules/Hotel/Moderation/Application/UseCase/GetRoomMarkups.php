<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\UseCase;

use Module\Hotel\Moderation\Application\Response\RoomMarkupsDto;
use Module\Hotel\Moderation\Domain\Hotel\Repository\RoomMarkupSettingsRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetRoomMarkups implements UseCaseInterface
{
    public function __construct(
        private readonly RoomMarkupSettingsRepositoryInterface $markupSettingsRepository
    ) {
    }

    public function execute(int $roomId): ?RoomMarkupsDto
    {
        $markup = $this->markupSettingsRepository->get($roomId);
        if ($markup === null) {
            return null;
        }

        return new RoomMarkupsDto($markup->discount()->value());
    }
}
