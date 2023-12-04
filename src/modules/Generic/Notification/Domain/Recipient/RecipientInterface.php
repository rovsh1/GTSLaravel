<?php

namespace Module\Generic\Notification\Domain\Recipient;

use Module\Generic\Notification\Domain\Enum\RecipientTypeEnum;

interface RecipientInterface
{
    public function type(): RecipientTypeEnum;

    public function isEqual(RecipientInterface $recipient): bool;

    public function toArray(): array;
}