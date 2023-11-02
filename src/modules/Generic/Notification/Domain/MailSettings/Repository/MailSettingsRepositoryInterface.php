<?php

namespace Module\Generic\Notification\Domain\MailSettings\Repository;

use Module\Generic\Notification\Domain\MailSettings\MailSettings;
use Module\Generic\Notification\Domain\Shared\Enum\NotificationTypeEnum;

interface MailSettingsRepositoryInterface
{
    public function find(NotificationTypeEnum $id): MailSettings;

    public function store(MailSettings $rule): void;
}