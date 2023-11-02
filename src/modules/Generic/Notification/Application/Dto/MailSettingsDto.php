<?php

namespace Module\Generic\Notification\Application\Dto;

final class MailSettingsDto
{
    /**
     * @param string $id
     * @param string $name
     * @param MailRecipientDto[] $recipients
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly array $recipients,
    ) {
    }
}