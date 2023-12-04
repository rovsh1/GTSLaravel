<?php

namespace Module\Generic\Notification\Domain\Service;

use Module\Generic\Notification\Domain\Dto\MailDataDto;
use Module\Generic\Notification\Domain\Dto\RecipientListDto;
use Module\Generic\Notification\Domain\Entity\MailSettings;

interface RecipientResolverInterface
{
    public function resolve(MailSettings $settings, MailDataDto $data): RecipientListDto;
}