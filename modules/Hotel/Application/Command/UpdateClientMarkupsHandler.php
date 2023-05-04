<?php

declare(strict_types=1);

namespace Module\Hotel\Application\Command;

use Custom\Framework\Contracts\Bus\CommandHandlerInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;
use Module\Hotel\Domain\Repository\MarkupSettingsRepositoryInterface;

class UpdateClientMarkupsHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly MarkupSettingsRepositoryInterface $repository
    ) {}

    public function handle(CommandInterface|UpdateClientMarkups $command)
    {
        $this->repository->updateClientMarkups(
            hotelId: $command->hotelId,
            individual: $command->individual,
            OTA: $command->OTA,
            TA: $command->TA,
            TO: $command->TO
        );
    }
}
