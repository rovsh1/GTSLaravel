<?php

namespace Module\Services\NotificationManager\Domain\Entity\Recipient;

class Administrator implements RecipientInterface
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