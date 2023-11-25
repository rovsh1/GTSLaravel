<?php

namespace Sdk\Shared\Support\ApplicationContext\Concerns;

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