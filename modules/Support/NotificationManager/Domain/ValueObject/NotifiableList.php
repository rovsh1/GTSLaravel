<?php

namespace Module\Support\NotificationManager\Domain\ValueObject;

use Iterator;
use Module\Support\NotificationManager\Domain\Entity\NotifiableInterface;

class NotifiableList implements Iterator
{
    private array $list = [];

    private int $position = 0;

    public function __construct(
        array $notifiableList = []
    ) {
        $this->merge($notifiableList);
    }

    public function merge(NotifiableList|array $list): void
    {
        if (is_array($list)) {
            $this->validateList($list);
        }

        foreach ($list as $notifiable) {
            if (!$this->has($notifiable)) {
                $this->list[] = $notifiable;
            }
        }
    }

    public function has(NotifiableInterface $notifiable): bool
    {
        foreach ($this->list as $item) {
            if ($item->isEqual($notifiable)) {
                return true;
            }
        }
        return false;
    }

    public function current(): mixed
    {
        return $this->list[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->list[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    private function validateList(array $list): void
    {
        foreach ($list as $notifiable) {
            if (!$notifiable instanceof NotifiableInterface) {
                throw new \Exception('Notifiable instance error');
            }
        }
    }
}
