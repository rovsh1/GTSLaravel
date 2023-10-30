<?php

namespace Module\Generic\NotificationManager\Domain\Entity;

use Module\Generic\NotificationManager\Domain\ValueObject\NotifiableTypeEnum;

class Notifiable
{
    public function __construct(
        private readonly NotifiableTypeEnum $type,
        private readonly ?int $notifiableId,
    ) {
    }

    public function type(): NotifiableTypeEnum
    {
        return $this->type;
    }

    public function notifiableId(): ?int
    {
        return $this->notifiableId;
    }
}
