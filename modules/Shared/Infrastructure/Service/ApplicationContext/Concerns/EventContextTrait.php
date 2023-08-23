<?php

namespace Module\Shared\Infrastructure\Service\ApplicationContext\Concerns;

trait EventContextTrait
{
    public function setEvent(string $id): void
    {
        $this->set('event', $id);
    }

    public function event(): ?string
    {
        return $this->get('event');
    }
}