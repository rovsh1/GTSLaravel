<?php

namespace Module\Generic\Notification\Application\UseCase\MailSettings;

use Module\Generic\Notification\Application\Dto\MailRecipientDto;
use Module\Generic\Notification\Application\Dto\MailSettingsDto;
use Module\Generic\Notification\Domain\MailSettings\Repository\MailSettingsRepositoryInterface;
use Module\Generic\Notification\Domain\Shared\Enum\NotificationTypeEnum;
use Module\Shared\Contracts\Service\TranslatorInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GatMailSettings implements UseCaseInterface
{
    public function __construct(
        private readonly MailSettingsRepositoryInterface $mailSettingsRepository,
        private readonly TranslatorInterface $translator,
    ) {
    }

    /**
     * @return MailSettingsDto[]
     */
    public function execute(): array
    {
        $list = [];
        foreach (NotificationTypeEnum::cases() as $case) {
            $settings = $this->mailSettingsRepository->find($case);
            $list[] = new MailSettingsDto(
                id: $case->name,
                name: $this->translator->translateEnum($case),
                recipients: array_map(fn($r) => new MailRecipientDto(
                    type: $r->type()->value,
                    payload: $r->toArray()
                ), $settings->recipients())
            );
        }

        return $list;
    }
}