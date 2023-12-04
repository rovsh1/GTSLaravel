<?php

namespace Module\Generic\Notification\Application\UseCase\MailSettings;

use Exception;
use Module\Generic\Notification\Application\Dto\MailRecipientDto;
use Module\Generic\Notification\Domain\Enum\NotificationTypeEnum;
use Module\Generic\Notification\Domain\Factory\RecipientFactory;
use Module\Generic\Notification\Domain\Repository\MailSettingsRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateRecipients implements UseCaseInterface
{
    public function __construct(
        private readonly MailSettingsRepositoryInterface $mailSettingsRepository,
    ) {
    }

    /**
     * @param string $settingsId
     * @param MailRecipientDto[] $recipients
     * @return void
     * @throws Exception
     */
    public function execute(string $settingsId, array $recipients): void
    {
        $type = NotificationTypeEnum::fromName($settingsId);
        $settings = $this->mailSettingsRepository->find($type);
        $recipientFactory = new RecipientFactory();
        $settings->updateRecipients(
            array_map(fn($r) => $recipientFactory->fromPayload([
                'type' => $r->type,
                ...$r->payload
            ]), $recipients)
        );
        $this->mailSettingsRepository->store($settings);
    }
}