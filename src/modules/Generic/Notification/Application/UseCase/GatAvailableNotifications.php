<?php

namespace Module\Generic\Notification\Application\UseCase;

use Module\Generic\Notification\Application\Dto\NotificationDto;
use Module\Generic\Notification\Domain\Shared\Enum\NotificationTypeEnum;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Contracts\Service\TranslatorInterface;

class GatAvailableNotifications implements UseCaseInterface
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {
    }

    /**
     * @return NotificationDto[]
     */
    public function execute(): array
    {
        $list = [];
        foreach (NotificationTypeEnum::cases() as $case) {
            $list[] = new NotificationDto(
                id: $case->name,
                name: $this->translator->translateEnum($case)
            );
        }

        return $list;
    }
}