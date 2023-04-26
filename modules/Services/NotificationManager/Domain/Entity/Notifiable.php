<?php

namespace Module\Services\NotificationManager\Domain\Entity;

use Module\Services\NotificationManager\Domain\ValueObject\NotifiableTypeEnum;

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
