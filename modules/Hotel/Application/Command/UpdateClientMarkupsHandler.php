<?php

declare(strict_types=1);

namespace Module\Hotel\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Hotel\Domain\Service\MarkupSettingsUpdater;

class UpdateClientMarkupsHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly MarkupSettingsUpdater $service
    ) {}

    public function handle(CommandInterface|UpdateClientMarkups $command): bool
    {
        return $this->service->updateClientMarkups(
            hotelId: $command->hotelId,
            individual: $command->individual,
            OTA: $command->OTA,
            TA: $command->TA,
            TO: $command->TO
        );
    }
}
