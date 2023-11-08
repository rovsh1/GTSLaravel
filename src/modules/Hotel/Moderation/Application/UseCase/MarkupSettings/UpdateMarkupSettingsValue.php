<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\UseCase\MarkupSettings;

use Module\Hotel\Moderation\Application\Enums\UpdateMarkupSettingsActionEnum;
use Module\Hotel\Moderation\Application\Service\MarkupSettingsSetter\MarkupSettingsSetter;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateMarkupSettingsValue implements UseCaseInterface
{
    public function __construct(
        private readonly MarkupSettingsSetter $markupSettingsUpdater
    ) {
    }

    public function execute(int $hotelId, string $key, mixed $value, UpdateMarkupSettingsActionEnum $action): void
    {
        $this->markupSettingsUpdater->update($hotelId, $key, $value, $action);
    }
}
