<?php

namespace Module\Shared\Infrastructure\Service\ApplicationContext\Concerns;

trait EntityContextTrait
{
    public function setEntity(string $class, int $id): void
    {
        $this->set('entity', [
            'class' => $class,
            'id' => $id
        ]);
    }
}