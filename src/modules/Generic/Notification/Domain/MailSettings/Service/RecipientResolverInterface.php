<?php

namespace Module\Generic\Notification\Domain\MailSettings\Service;

use Module\Generic\Notification\Domain\MailSettings\Dto\RecipientListDto;
use Module\Generic\Notification\Domain\MailSettings\Dto\MailDataDto;
use Module\Generic\Notification\Domain\MailSettings\MailSettings;

interface RecipientResolverInterface
{
    public function resolve(MailSettings $settings, MailDataDto $data): RecipientListDto;
}