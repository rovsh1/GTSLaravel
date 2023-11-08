<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\UseCase;

use Module\Hotel\Moderation\Application\Dto\MarkupSettingsDto;
use Module\Hotel\Moderation\Application\Factory\MarkupSettingsDtoFactory;
use Module\Hotel\Moderation\Infrastructure\Repository\MarkupSettingsRepository;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetMarkupSettings implements UseCaseInterface
{
    public function __construct(
        private readonly MarkupSettingsRepository $markupSettingsRepository
    ) {
    }

    public function execute(int $hotelId): MarkupSettingsDto
    {
        $markup = $this->markupSettingsRepository->get($hotelId);

        return (new MarkupSettingsDtoFactory())->from($markup);
    }
}
