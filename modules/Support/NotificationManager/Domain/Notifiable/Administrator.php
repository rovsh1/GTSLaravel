<?php

namespace Module\Support\NotificationManager\Domain\Notifiable;

use Module\Support\NotificationManager\Domain\Entity\NotifiableInterface;

class Administrator implements NotifiableInterface
{
    private string $administratorId;

    public function __construct(int $administratorId)
    {
        $this->setAdministratorId($administratorId);
    }

    public function setAdministratorId(int $administratorId)
    {
        $this->administratorId = $administratorId;
    }

    public function administratorId(): int
    {
        return $this->administratorId;
    }
}