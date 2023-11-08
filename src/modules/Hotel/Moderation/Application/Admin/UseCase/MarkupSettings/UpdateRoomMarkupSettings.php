<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Admin\UseCase\MarkupSettings;

use Module\Hotel\Moderation\Application\Admin\Command\Room\UpdateMarkupSettingsValue as Command;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateRoomMarkupSettings implements UseCaseInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {
    }

    public function execute(int $roomId, string $key, mixed $value): void
    {
        $this->commandBus->execute(new Command($roomId, $key, $value));
    }
}
