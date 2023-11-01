<?php

declare(strict_types=1);

namespace Module\Catalog\Application\Admin\UseCase\MarkupSettings;

use Module\Catalog\Application\Admin\Command\UpdateMarkupSettingsValue as Command;
use Module\Catalog\Application\Admin\Enums\UpdateMarkupSettingsActionEnum;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateMarkupSettingsValue implements UseCaseInterface
{

    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
    }

    public function execute(int $hotelId, string $key, mixed $value, UpdateMarkupSettingsActionEnum $action): void
    {
        $this->commandBus->execute(new Command($hotelId, $key, $value, $action));
    }

}
