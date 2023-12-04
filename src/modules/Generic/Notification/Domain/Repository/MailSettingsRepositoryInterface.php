<?php

namespace Module\Generic\Notification\Domain\Repository;

use Module\Generic\Notification\Domain\Entity\MailSettings;
use Module\Generic\Notification\Domain\Enum\NotificationTypeEnum;

interface MailSettingsRepositoryInterface
{
    public function find(NotificationTypeEnum $id): MailSettings;

    public function store(MailSettings $rule): void;
}