<?php

namespace Module\Generic\Notification\Domain\MailSettings;

use Module\Generic\Notification\Domain\MailSettings\ValueObject\Recipient\RecipientInterface;
use Module\Generic\Notification\Domain\Shared\Enum\NotificationTypeEnum;

class MailSettings
{
    private array $recipients;

    /**
     * @param NotificationTypeEnum $id
     * @param RecipientInterface[] $recipients
     */
    public function __construct(
        private readonly NotificationTypeEnum $id,
        array $recipients
    ) {
        $this->recipients = $this->validatedRecipients($recipients);
    }

    public function id(): NotificationTypeEnum
    {
        return $this->id;
    }

    /**
     * @return RecipientInterface[]
     */
    public function recipients(): array
    {
        return $this->recipients;
    }

    /**
     * @param RecipientInterface[] $recipients
     * @return void
     */
    public function updateRecipients(array $recipients): void
    {
        $this->recipients = $this->validatedRecipients($recipients);
    }

    private function validatedRecipients(array $recipients): array
    {
//        if (empty($recipients)) {
//            throw new \InvalidArgumentException('Recipients');
//        }
        $unique = [];
        foreach ($recipients as $recipient) {
            if (!$recipient instanceof RecipientInterface) {
                throw new \InvalidArgumentException('Recipient must be instance of RecipientInterface');
            }
            foreach ($unique as $r) {
                if ($recipient->isEqual($r)) {
                    continue 2;
                }
            }
            $unique[] = $recipient;
        }

        return $unique;
    }
}