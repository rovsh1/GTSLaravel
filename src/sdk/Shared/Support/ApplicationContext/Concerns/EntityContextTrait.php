<?php

namespace Sdk\Shared\Support\ApplicationContext\Concerns;

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