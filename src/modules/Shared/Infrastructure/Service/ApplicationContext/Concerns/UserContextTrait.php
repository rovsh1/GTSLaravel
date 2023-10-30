<?php

namespace Module\Shared\Infrastructure\Service\ApplicationContext\Concerns;

trait UserContextTrait
{
    public function setUserId(int $id): void
    {
        $this->set('userId', $id);
    }

    public function userId(): ?int
    {
        return $this->get('userId');
    }
}