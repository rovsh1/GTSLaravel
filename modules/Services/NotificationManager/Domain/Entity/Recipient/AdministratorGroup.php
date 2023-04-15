<?php

namespace Module\Services\NotificationManager\Domain\Entity\Recipient;

class AdministratorGroup implements RecipientInterface
{
    private string $groupId;

    public function __construct(int $groupId)
    {
        $this->setGroupId($groupId);
    }

    public function setGroupId(int $groupId)
    {
        $this->groupId = $groupId;
    }

    public function groupId(): int
    {
        return $this->groupId;
    }
}